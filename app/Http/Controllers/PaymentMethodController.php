<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {

        $user = Auth::user();

        // Validate the request based on the selected payment method
        $request->validate([
            'method' => 'required|in:online,bank',
            'paypal_address' => 'required_if:method,online|email|nullable',
            'bank_name' => 'required_if:method,bank|string|max:255|nullable',
            'account_name' => 'required_if:method,bank|string|max:255|nullable',
            'account_number' => 'required_if:method,bank|string|max:255|nullable',
            'bank_country' => 'required_if:method,bank|string|max:255|nullable',
        ]);

         // Store the payment method details
         $paymentMethod = PaymentMethod::create([
            'user_id' => $user->id,
            'method' => $request->method,
            'paypal_address' => $request->paypal_address,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'bank_country' => $request->bank_country,
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Payment method saved successfully!',
            'data' => $paymentMethod
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Attempt to find the payment method by ID
        $paymentMethod = PaymentMethod::find($id);

        // If the payment method does not exist, return a 404 response
        if (!$paymentMethod) {
            return response()->json([
                'message' => 'Payment method does not exist.'
            ], 404);
        }

        // Validate the request data based on the selected method
        if ($request->method == 'online') {
            $request->validate([
                'paypal_address' => 'required|email',
            ]);

            // Update the payment method with PayPal details
            $paymentMethod->update([
                'method' => 'online',
                'paypal_address' => $request->paypal_address,
                'bank_name' => null, // Clear bank details if switching to PayPal
                'account_name' => null,
                'account_number' => null,
                'bank_country' => null,
            ]);

        } elseif ($request->method == 'bank') {
            $request->validate([
                'bank_name' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:20',
                'bank_country' => 'required|string|max:255',
            ]);

            // Update the payment method with bank details
            $paymentMethod->update([
                'method' => 'bank',
                'paypal_address' => null, 
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'bank_country' => $request->bank_country,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid payment method selected.'
            ], 400);
        }

        return response()->json([
            'message' => 'Payment method updated successfully!',
            'payment_method' => $paymentMethod
        ], 200);
    }

}
