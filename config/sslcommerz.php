<?php

return [
    'store_id' => env('SSLCOMMERZ_STORE_ID', ''),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD', ''),
    'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
    'success_url' => env('SSLCOMMERZ_SUCCESS_URL', '/payment/success'),
    'fail_url' => env('SSLCOMMERZ_FAIL_URL', '/payment/fail'),
    'cancel_url' => env('SSLCOMMERZ_CANCEL_URL', '/payment/cancel'),
    'ipn_url' => env('SSLCOMMERZ_IPN_URL', '/payment/ipn'),
];
