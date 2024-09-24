<?php

namespace App\Http\Controllers;

use App\Models\CardsDetails;
use App\Models\PaystackPayment;
use App\Models\Subscription;
use App\Traits\Paystack;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class PaystackController extends Controller
{
    use Paystack;
    //

    public function store(Request $request)
    {
        $id=Auth::id();

        

        $attributes=$request->validate([
            'amount' => 'required'
        ]);

        try {
            $user=User::find($id);
            $currencycode='NGN';
      

            if($attributes['amount'] < 0)
            {
            $response=[
            'status' => false,
            'message' => 'Minimum amount is 100',
           ];

           $code=422;
            }
            
            $reference = $user->firstname.rand(0,1000000);

           $data=$this->createInitiliazeUrl($attributes['amount'],$user->email);
        //    dd($data);



           $response=[
            'status' => true,
            'url' => $data->data->authorization_url,
           ];

           $code=200;

        } catch (\Throwable $th) {
            //throw $th;
            $response=[
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ];

            $code=400;
        }

        return response()->json($response,$code);
    }




    public function verifyPayment(Request $request)
    {
    
        $attributes = $request->validate([
            "reference_code" => 'required'
        ]);

        //verify the payment from Paystack
        $data = $this->verifyPaystackPayment($attributes['reference_code']);
        // dd($data);
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
            $authorizationCode = $data->data->authorization->authorization_code;
            $cardType = $data->data->authorization->card_type;
            $expMonth = $data->data->authorization->exp_month;
            $expYear = $data->data->authorization->exp_year;
            $last4 = $data->data->authorization->last4;
            $bank = $data->data->authorization->bank;
            $reusable = $data->data->authorization->reusable;
            $countryCode = $data->data->authorization->country_code;
            $brand = $data->data->authorization->brand;

            

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

                // get users card details
                CardsDetails::updateOrCreate([
                    'user_id' => $userId,
                    'authorization_code' => $authorizationCode,
                    'card_type' => $cardType,
                    'exp_month' => $expMonth,
                    'exp_year' => $expYear,
                    'last4' => $last4,
                    'bank' => $bank,
                    'reuseable' => $reusable,
                    'country_code' => $countryCode,
                    'brand' => $brand,
                ]);

                //add into subscription table
                //set the expiry date
            $currentdate=Carbon::now();
            $expirydate=Carbon::now()->addDays(30);
            Subscription::updateOrCreate([
                'user_id' => $userId,
                'amount' => $amount,
                'subscription_start_date' => $currentdate,
                'subscription_end_date' => $expirydate
            ]);
            
            //update the user plan from basic to premium
                User::where('id',$userId)->update([
                    'plan_type' => 'premium'
                ]);

            $response=[
                'status' => true,
                'message' => 'Subscription Successful'
            ];

                return response()->json($response,200);
            }
        }
    }



    public function handleRecurringPayment()
    {
        $id = Auth::id();
        
        $currentdate=Carbon::now();
        $expirydate=Carbon::now()->addDays(30);

        $user = User::find($id);
        // Check for expired subscriptions
        $subscription = Subscription::where('user_id',$id)->orderBy('created_at','DESC')->first();

       
        
        if(isset($subscription))
        {
           
            $endDate=Carbon::parse($subscription->subscription_end_date);
            if($currentdate > $subscription->subscription_end_date)
            {
                
               // Check if the card is reusable
               $cardDetails = CardsDetails::where('user_id', $id)->orderBy('created_at', 'DESC')->first();

               if(isset($cardDetails) && $cardDetails->reuseable == 1)
                {
                    //attempt to charge the card
                    $response = $this->recurringPayment($cardDetails->authorization_code,$user->email,$subscription->amount);

                    
                    if ($response->data->status == 'success') {
                        Subscription::updateOrCreate([
                            'user_id' => $id,
                            'amount' => $subscription->amount,
                            'subscription_start_date' => $currentdate,
                            'subscription_end_date' => $expirydate
                        ]);
                    }else{
                        //send email to the user that you are tryin to charge their cards
                    }
                }

            }
        }else{
            //send email to the admin
        } 

        return response()->json(['message' => 'Recurring payments processed.']);
    }


    
}
