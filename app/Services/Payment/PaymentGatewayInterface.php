<?php

namespace App\Services\Payment;

use App\Models\PaymentTransaction;
use App\Models\User;

interface PaymentGatewayInterface
{
    /**
     * Initiate payment transaction with external gateway.
     */
    public function initiatePayment(PaymentTransaction $transaction, User $user): array;

    /**
     * Validate payment transaction with external gateway server.
     */
    public function validateTransaction(string $valId, string $tranId, float $amount, string $currency): array;
}
