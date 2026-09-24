<?php

namespace App\Services\Payment;

use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerzPaymentGateway implements PaymentGatewayInterface
{
    protected string $storeId;
    protected string $storePassword;
    protected bool $isSandbox;
    protected string $baseUrl;
    protected string $validationUrl;

    public function __construct()
    {
        $this->storeId = (string) config('sslcommerz.store_id');
        $this->storePassword = (string) config('sslcommerz.store_password');
        $this->isSandbox = (bool) config('sslcommerz.sandbox', true);

        if ($this->isSandbox) {
            $this->baseUrl = 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';
            $this->validationUrl = 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';
        } else {
            $this->baseUrl = 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
            $this->validationUrl = 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
        }
    }

    public function initiatePayment(PaymentTransaction $transaction, User $user): array
    {
        if (empty($this->storeId) || empty($this->storePassword)) {
            return [
                'status' => 'FAILED',
                'message' => 'SSLCommerz gateway store credentials are missing from server configuration.',
            ];
        }

        $appUrl = rtrim(config('app.url'), '/');
        $successUrl = $appUrl . route('payment.success', [], false);
        $failUrl = $appUrl . route('payment.fail', [], false);
        $cancelUrl = $appUrl . route('payment.cancel', [], false);
        $ipnUrl = $appUrl . route('payment.ipn', [], false);

        $profile = $user->memberProfile;

        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => number_format((float) $transaction->amount, 2, '.', ''),
            'currency' => strtoupper($transaction->currency),
            'tran_id' => $transaction->transaction_id,
            'success_url' => $successUrl,
            'fail_url' => $failUrl,
            'cancel_url' => $cancelUrl,
            'ipn_url' => $ipnUrl,
            'cus_name' => $profile?->full_name ?: $user->name,
            'cus_email' => $user->email,
            'cus_add1' => $profile?->city ?: 'Dhaka',
            'cus_city' => $profile?->city ?: 'Dhaka',
            'cus_country' => $profile?->country ?: 'Bangladesh',
            'cus_phone' => $profile?->phone ?: '01700000000',
            'shipping_method' => 'NO',
            'product_name' => '2nd Nikah Membership - ' . ($transaction->membershipPlan->name ?? 'Plan'),
            'product_category' => 'Matrimonial Service',
            'product_profile' => 'non-physical-goods',
        ];

        try {
            $response = Http::asForm()->post($this->baseUrl, $postData);

            if (! $response->successful()) {
                return [
                    'status' => 'FAILED',
                    'message' => 'HTTP request to SSLCommerz API failed.',
                ];
            }

            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'SUCCESS' && ! empty($data['GatewayPageURL'])) {
                return [
                    'status' => 'SUCCESS',
                    'gateway_url' => $data['GatewayPageURL'],
                    'session_key' => $data['sessionkey'] ?? null,
                ];
            }

            return [
                'status' => 'FAILED',
                'message' => $data['failedreason'] ?? 'SSLCommerz initialization failed.',
            ];
        } catch (\Throwable $e) {
            Log::error('SSLCommerz initiation exception: ' . $e->getMessage());

            return [
                'status' => 'FAILED',
                'message' => 'Gateway error: ' . $e->getMessage(),
            ];
        }
    }

    public function validateTransaction(string $valId, string $tranId, float $amount, string $currency): array
    {
        if (empty($valId)) {
            return [
                'status' => 'FAILED',
                'message' => 'Validation ID is missing.',
            ];
        }

        try {
            $queryUrl = $this->validationUrl . '?' . http_build_query([
                'val_id' => $valId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format' => 'json',
            ]);

            $response = Http::get($queryUrl);

            if (! $response->successful()) {
                return [
                    'status' => 'FAILED',
                    'message' => 'Failed to reach SSLCommerz validation server.',
                ];
            }

            $data = $response->json();

            $status = $data['status'] ?? '';
            $returnedAmount = (float) ($data['amount'] ?? 0);
            $returnedCurrency = strtoupper((string) ($data['currency'] ?? ''));

            if ($status !== 'VALID' && $status !== 'VALIDATED') {
                return [
                    'status' => 'FAILED',
                    'message' => 'SSLCommerz transaction validation status is invalid: ' . $status,
                ];
            }

            // Amount check with 0.01 tolerance
            if (abs($returnedAmount - $amount) > 0.01) {
                return [
                    'status' => 'FAILED',
                    'message' => "Transaction amount mismatch: expected {$amount}, got {$returnedAmount}.",
                ];
            }

            // Currency check
            if ($returnedCurrency !== strtoupper($currency)) {
                return [
                    'status' => 'FAILED',
                    'message' => "Transaction currency mismatch: expected {$currency}, got {$returnedCurrency}.",
                ];
            }

            return [
                'status' => 'SUCCESS',
                'bank_transaction_id' => $data['bank_tran_id'] ?? $valId,
                'response' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('SSLCommerz validation exception: ' . $e->getMessage());

            return [
                'status' => 'FAILED',
                'message' => 'Gateway validation exception: ' . $e->getMessage(),
            ];
        }
    }
}
