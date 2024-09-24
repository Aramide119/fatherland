<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Paypal;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserTransaction;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Helpers\UniqueNumberHelper;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTransaction;
use Srmklive\PayPal\Services\ExpressCheckout;



class PaypalController extends Controller
{
    public function handlePayment(Request $request)
    {
        $request->validate([
            'billing_cycle' => [
                "required",Rule::in(['MONTHLY', 'YEARLY'])
            ],
        ]);

        $billingCycle =  $request->billing_cycle;

        $planId = $billingCycle == "MONTHLY"? "P-79T6625621282323KMXK76KY" : "P-56B43161AG6921408MXK774I";

        $paypal = new Paypal;

        $response = $paypal->createSubscription( $planId, $request->user()->email);

        $subscriptionTransaction = new SubscriptionTransaction;

        $subscriptionTransaction->user_id =$request->user()->id;
        $subscriptionTransaction->paypal_subscription_id =   $response['id'];
        $subscriptionTransaction->billing_cycle =   $billingCycle;
        $subscriptionTransaction->status =    $response['status'];
        $subscriptionTransaction->save();

        return response()->json(["redirectUrl"=>$response['links'][0]['href']]);
    }

    function showSubscriptionDetails(string $subscriptionId) {
        $paypal = new Paypal;

       $response = $paypal->showSubscriptionDetails($subscriptionId);

        $expiresAt = \carbon\Carbon::parse($response['billing_info']['next_billing_time'])->format("Y-m-d H:i:s");

        $subscriptionTransaction = SubscriptionTransaction::where('paypal_subscription_id', $subscriptionId)
       ->first(); // Retrieve the model instance, not just updating
        // dd($subscriptionTransaction->user);
        if ($subscriptionTransaction) {
            // Update the model attributes
            $subscriptionTransaction->update([
                "status" => $response['status'],
                "expires_at" => $expiresAt,
            ]);
        }

        // Access the user associated with the transaction
        $user = $subscriptionTransaction->user;

        if ($user) {

           $updatePlan = User::where('id', $user->id);
            // Update the user's account type to 'premium'
            $updatePlan->update(['plan_type' => 'premium']);
             // Generate and save the unique number
            $uniqueNumber = UniqueNumberHelper::saveUniqueNumber($user->id);

        }


        if ($subscriptionTransaction && $response['status'] === 'active') {
            // Fetch the updated subscription transaction
            $updatedTransaction = SubscriptionTransaction::where('paypal_subscription_id', $subscriptionId)->first();

            if ($updatedTransaction) {
                // Access the user associated with the transaction
                $user = $updatedTransaction->user;

                if ($user) {
                    // Update the user's account type to 'premium'
                    $updatePlan = User::where('id', $user->id);
                    // Update the user's account type to 'premium'
                    $updatePlan->update(['plan_type' => 'premium']);

                }
            }
        }

        return response()->json(['message' => 'Your subscription payment is successful!!']);
    }

    public function paymentCancel()
    {
        return response()->json(['message' => 'Your payment has been declined. The payment cancellation page goes here!']);
    }

    public function cancelSubscription(Request $request)
    {
        $request->validate(['subscription_id' => ["required"]]);

        $subscriptionId = $request->subscription_id;

        $paypal = new Paypal;

        $response = $paypal->cancelSubscription($subscriptionId);

        $response = $paypal->showSubscriptionDetails($subscriptionId);

        // $expiresAt = \carbon\Carbon::parse($response['billing_info']['next_billing_time'])->format("Y-m-d H:i:s");

        $subscriptionTransaction = SubscriptionTransaction::where('paypal_subscription_id', $subscriptionId)
       ->first(); // Retrieve the model instance, not just updating
        // dd($subscriptionTransaction->user);
        if ($subscriptionTransaction) {
            // Update the model attributes
            $subscriptionTransaction->update([
                "status" => $response['status'],
                // "expires_at" => $expiresAt,
            ]);
        }


        return response()->json(['message' => 'Your subscription has been cancelled successfully!']);
    }

    public function paymentSuccess(Request $request)
    {
       return  $this->showSubscriptionDetails($request->subscription_id);
    }

    function subscriptionWebhookCallback(Request $request)  {
        return  $this->showSubscriptionDetails($request->resource['id']);
    }


    public function initiateCheckout(Request $request)
    {
        $referenceId = Str::uuid()->toString();

        $request->validate([
            'amount' => 'required|numeric',
        ]);

        // Retrieve the reference ID and amount from the request
        $referenceId = $referenceId;
        $amount = $request->input('amount');

        $paypal = new Paypal;

        // Initiate the PayPal checkout process
        $response = $paypal->initiatePaypalCheckout($referenceId, $amount);

        $checkoutTransaction = new UserTransaction;

        $checkoutTransaction->user_id =$request->user()->id;
        $checkoutTransaction->paypal_checkout_id =   $response['id'];
        $checkoutTransaction->status =    $response['status'];
        $checkoutTransaction->save();

        // Return the response
         response()->json($response);

        return response()->json(["redirectUrl"=>$response['links'][1]['href']]);
    }

     function checkoutSuccess(string $referenceId){
        $paypal = new Paypal;

        $response = $paypal->confirmPayPalCheckout($referenceId);

         $checkoutTransactionDetails = UserTransaction::where('paypal_checkout_id', $referenceId)
        ->first();
         if ($checkoutTransactionDetails) {
             // Update the model attributes
             $checkoutTransactionDetails->update([
                 "status" => $response['status'],
             ]);
         }

         return response()->json(['message' => 'Checkout is successful!!']);

    }

    public function checkoutSuccessDetails(Request $request)
    {
       return  $this->checkoutSuccess($request->reference_id);
    }
}
