<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

 class Paypal
{

  private $live_url;

  private $sandbox_url;

  private $secret;

  private $client_id;

  private $mode;

  private $url;

  private $returnUrl;

  private $cancelUrl;

  function __construct() {
    $this->live_url = config('services.paypal.live_url');
    $this->sandbox_url = config('services.paypal.sandbox_url');
    $this->secret = config('services.paypal.secret');
    $this->client_id = config('services.paypal.client_id');
    $this->mode = config('services.paypal.mode');
    $this->url = $this->mode =="sandbox"? $this->sandbox_url:$this->live_url;
    $this->returnUrl = config('services.paypal.return_url');
    $this->cancelUrl = config('services.paypal.cancel_url');
  }

  function createSubscription(string $planId , string $userEmail) : array {

    // return [$this->client_id,$this->secret];
    $headers = [
        'Content-Type'=> 'application/json',
        'Accept'=> 'application/json',
        'PayPal-Request-Id'=> Str::random(21),
        'Prefer'=> 'return=representation',
    ];



    $body = [
        "plan_id" => $planId,
        "subscriber" => [
            "email_address" => $userEmail
        ],
        "application_context" => [
            "brand_name" => config('app.name'),
            "locale" => "en-US",
            "shipping_preference" => "SET_PROVIDED_ADDRESS",
            "user_action" => "SUBSCRIBE_NOW",
            "payment_method" => [
                "payer_selected" => "PAYPAL",
                "payee_preferred" => "IMMEDIATE_PAYMENT_REQUIRED",
            ],
            "return_url" => $this->returnUrl,
            "cancel_url" => $this->cancelUrl,
        ],
    ];

    $response = Http::withBasicAuth($this->client_id, $this->secret)->withHeaders($headers)->post($this->url."/v1/billing/subscriptions",$body)->json();

    return $response;
  }


  function showSubscriptionDetails(string $subscriptionId) : array {

    $headers = [
        'Content-Type'=> 'application/json',
        'Accept'=> 'application/json',
        'X-PAYPAL-SECURITY-CONTEXT' => '{"scopes":["https://api-m.paypal.com/v1/subscription/.*","https://uri.paypal.com/services/subscription","openid"]}'
    ];

    $response = Http::withBasicAuth($this->client_id, $this->secret)->withHeaders($headers)->get($this->url."/v1/billing/subscriptions/{$subscriptionId}")->json();

    return $response;
  }

  function cancelSubscription(string $subscriptionId) {
    $headers = [
      'Content-Type'=> 'application/json',
      'Accept'=> 'application/json',
      'X-PAYPAL-SECURITY-CONTEXT' => '{"scopes":["https://api-m.paypal.com/v1/subscription/.*","https://uri.paypal.com/services/subscription","openid"]}'
    ];

    $body = [
      "reason" => "Done with with the service, and totally satisfied"
    ];

    $response = Http::withBasicAuth($this->client_id, $this->secret)->withHeaders($headers)->post($this->url."/v1/billing/subscriptions/{$subscriptionId}/cancel",$body)->json();

    return $response;
  }

  function getAccessToken() {
    $clientId = '1';
    $secret = '9KloWtkZaaVaLAVUqKTeF7EQWGpsGrIcP16lre0y';

    $response = Http::withBasicAuth($clientId, $secret)
        ->post('https://api-m.paypal.com/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

    if ($response->successful()) {
        $accessToken = $response['access_token'];
        return $accessToken;
    } else {
        // Handle error response from PayPal authentication service
        $error = $response->json();
        // Log or handle the error as per your application's requirements
        return null;
    }
  }

  function initiatePaypalCheckout(string $referenceId, float $amount) : array {

    $accessToken = [$this->client_id, $this->secret];

    $headers = [
        'Content-Type' => 'application/json',
        'PayPal-Request-Id' => Str::random(21),
        // 'Authorization' => '',
    ];

    $body = [
        "intent" => "CAPTURE",
        "purchase_units" => [
            [
                "reference_id" => $referenceId,
                "amount" => [
                    "currency_code" => "USD",
                    "value" => number_format($amount, 2, '.', ''),
                ],
                "shipping" => [ // Include the shipping address here
                    "address" => [ // Specify the shipping address
                        // Provide details of the shipping address
                        "address_line_1" => "123 Shipping Street",
                        "address_line_2" => "",
                        "admin_area_1" => "CA",
                        "admin_area_2" => "US",
                        "postal_code" => "12345",
                        "country_code" => "US"
                    ]
                ]
            ]
        ],
        "payment_source" => [
            "paypal" => [
                "experience_context" => [
                    "payment_method_preference" => "IMMEDIATE_PAYMENT_REQUIRED",
                    "brand_name" => "EXAMPLE INC",
                    "locale" => "en-US",
                    "landing_page" => "LOGIN",
                    "shipping_preference" => "SET_PROVIDED_ADDRESS",
                    "user_action" => "PAY_NOW",
                    "return_url" => $this->returnUrl,
                    "cancel_url" => $this->cancelUrl,
                ]
            ]
        ]
    ];


    $response = Http::withBasicAuth($this->client_id, $this->secret)->withHeaders($headers)
        ->post($this->url."/v2/checkout/orders", $body)
        ->json();

    return $response;
}


function confirmPayPalCheckout(string $referenceId) {
    $headers = [
        'Content-Type' => 'application/json',
        'Accept'=> 'application/json'
    ];

    $capture_order = Http::withBasicAuth($this->client_id, $this->secret)->withHeaders($headers)->post($this->url."/v2/checkout/orders/{$referenceId}/capture",['nat'=>'nat'])->json();

    $response = Http::withBasicAuth($this->client_id, $this->secret)->withHeaders($headers)->get($this->url."/v2/checkout/orders/{$referenceId}")->json();

    return $response;
}


}
