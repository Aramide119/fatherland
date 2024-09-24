<?php

return [
    'paystack' => [
        'baseurl' => 'https://api.paystack.co/transaction/',
        'secret_key' => env('paystack_sandbox_secretkey'),
        'public_key' => env('paystack_sandbox_publickey'),
    ],


    'paypal' => [
        'baseurl' => 'https://api-m.sandbox.paypal.com/v1/',
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
    ],
    

    // 'paystackChargesUrl' => [
    //     'baseurl' => 'https://api.paystack.co/transaction/charge_authorization/',
    //     'secret_key' => env('paystack_sandbox_secretkey'),
    //     'public_key' => env('paystack_sandbox_publickey'),
    // ],

    'sendchamp' => [
        'baseurl' => 'https://api.sendchamp.com/api/v1',
        'dev_key' => env('sendchamp_key')
    ],
]














?>