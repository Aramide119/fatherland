<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\Paystack;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaystackPayment;
use App\Mail\PromotionSuccessEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\SubscriptionDueReminder;
use App\Models\PromotePostSubscription;

class PromotePostSubscriptionController extends Controller
{
    use Paystack;

    public function createSubscription(Request $request, $postId)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        $validatedData = $request->validate([
            'promote_reason' => 'required|string',
            'location' => 'required|string',
            'age_range' => 'required|string',
            'gender' => 'required|string',
            'budget' => 'required|string',
            'duration' => 'required|string',
        ]);

        // Create a new subscription with start_date and end_date set to null
        $subscription = new PromotePostSubscription([
            'user_id' => $userId,
            'post_id' => $postId,
            'promote_reason' => $validatedData['promote_reason'],
            'location' => $validatedData['location'],
            'age_range' => $validatedData['age_range'],
            'gender' => $validatedData['gender'],
            'budget' => $validatedData['budget'],
            'duration' => $validatedData['duration'],
            'payment_status' => 'not active', // Automatically set to 'not active'
        ]);

        $subscription->save();

        return response()->json(['message' => 'You have requested to promote your post, please proceed to make payment'], 200);
    }


    public function MakePayment(Request $request)
    {
        $id=Auth::id();

        $referenceId = Str::uuid()->toString();

        $amount=$request->validate([
            'amount' => 'required'
        ]);

            $user=User::find($id);


            if($amount['amount'] < 0)
            {
            $response=[
            'status' => false,
            'message' => 'Minimum amount is 100',
           ];

           $code=422;
            }

            // Retrieve the reference ID and amount from the request
             $referenceId = $referenceId;

             $paypal = new Paypal;

             // Initiate the PayPal checkout process
             $response = $paypal->initiatePaypalCheckout($referenceId, $amount);

             $checkoutTransaction = new UserTransaction;

             $checkoutTransaction->user_id =$request->user()->id;
             $checkoutTransaction->paypal_checkout_id =   $response['id'];
             $checkoutTransaction->status =    $response['status'];
             $checkoutTransaction->save();




        return response()->json(["redirectUrl"=>$response['links'][1]['href']]);
    }



    public function verifyPostPayment(Request $request)
    {

        $attributes = $request->validate([
            "reference_code" => 'required'
        ]);

        //verify the payment from Paystack
        $data = $this->verifyPaystackPayment($attributes['reference_code']);

        if (!isset($data->data->status)) {
           $response=[
            'status' => false,
            'message' =>  'Reference Code not found'
           ];

           return response()->json($response,404);
        }

        if($data->data->status == 'success'){

            //generate a fatherland reference
            $fatherlandRef = 'father_land'.rand(0,10000000);

            //get the details from paystack Response
            $paystackRef= $data->data->reference;
            $email=$data->data->customer->email;
            $amount= $data->data->amount/100;
            $gatewayresponse = $data->data->gateway_response;


            //check the email with fatherland database

            $userdetails = User::where('email',$email)->first();
            $userId=$userdetails->id;

            //check if the reference number exist before
            $check = PaystackPayment::where('paystack_reference',$paystackRef)->where('verified_status', 'verified')->first();

                if(isset($check))
                {
                    $response=[
                    'status' => true,
                    'message' => 'Payment Verified Before. Thanks!'
                    ];

                    return response()->json($response, 422);
                }else{

                    //insert into the record paystack Transaction
                    PaystackPayment::create([
                        'user_id' => $userId,
                        'email' => $email,
                        'amount' => $amount,
                        'paystack_reference' => $paystackRef,
                        'paystack_response' => $gatewayresponse,
                        'status' => $data->data->status,
                        'verified_status' => 'verified',
                        'fatherland_reference' => $fatherlandRef,
                    ]);

                    $subscriptions = PromotePostSubscription::where('user_id', $userId)->get();

                    foreach ($subscriptions as $subscription) {
                        $duration = $subscription->duration;

                        $currentDate = Carbon::now();

                        // Use regular expression to extract numeric value
                        preg_match('/(\d+)/', $duration, $matches);

                        if (isset($matches[0])) {
                            $numericDuration = (int) $matches[0]; // Convert to integer
                            $expirydate = $currentDate->copy()->addDays($numericDuration);
                            // Now you have the expiry date for each subscription
                            // Do whatever you need to do with it
                        } else {
                            // Handle the case where the duration format is unexpected
                        }
                    }

                    //add into subscription table
                    //set the expiry date

                // dd($expirydate);


                // PromotePostSubscription::updateOrCreate([
                //     'user_id' => $userId,
                //     'budget' => $amount,

                // ]);

                //update the user plan from basic to premium
                $subscriptionId = PromotePostSubscription::where('user_id',$userId)->update([
                        'budget' => $amount,
                        'start_date' => $currentDate,
                        'end_date' => $expirydate,
                        'payment_status' => 'successful'
                    ]);

                    $user = User::where('email',$email)->first();

                    $subscription = PromotePostSubscription::where('user_id', $userId)->first();
                    // dd($subscription);

                    if ($subscription) {
                        $amountPaid = $subscription->budget;
                        $duration = $subscription->duration;
                        $dueDate = $subscription->end_date;
                        // Other logic related to the successful payment...
                        Mail::to($user->email)->send(new PromotionSuccessEmail($amountPaid, $dueDate, $user, $duration));
                    } else {
                        // Handle the case where the subscription with the given ID is not found
                    }




                    if ($subscription && $subscription->payment_status === 'successful') {
                        $currentDate = Carbon::now();
                        $dueDate = $subscription->end_date;

                        if ($currentDate->diffInDays($dueDate) === 3) {
                            event(new SubscriptionDueReminder($userId, $dueDate));
                        }
                    }

                $response=[
                    'status' => true,
                    'message' => 'Promote Post Subscription Successful'
                ];

                return response()->json($response, 200);


            }
        }
   }
}
