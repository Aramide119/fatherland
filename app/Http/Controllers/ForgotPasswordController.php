<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ForgotPassword;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class ForgotPasswordController extends Controller
{
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // set token expiry date
        $expiry =  Carbon::now()->addMinutes(10);

        // Store the reset token in the forgot_passwords table
        $send_token = ForgotPassword::updateOrCreate([
            'email' => $user->email,
            'token' => random_int(1000, 9999),
            'expiry_date' => $expiry,
        ]);

        $logos = Setting::latest()->first();
        
        // Send the reset email to the user
        Mail::to($user->email)->send(new ResetPasswordEmail($send_token->token, $user, $logos));

        if ($user) {
            $response=[
                'status' => true,
                'message' => 'Password reset email sent.'
            ];

            

            return response()->json($response, 200);
        }else{
            return response()->json(['message' => 'error'], 500);
        }

    }


    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $passwordReset = ForgotPassword::where('token', $request->token)->first();


        if (!$passwordReset) {
            return response()->json(['message' => 'Invalid reset token.'], 400);
        }
        $email = $passwordReset->email; // Accessing the email associated with this record

        $user = User::where('email', $email)->first();


        if (!$user) {

            $response =[
                'status' => true,
                'message' => 'User Not Found'
            ];

            return response()->json($response,404);
        }

        //check if token has expired
        $currentDate = Carbon::now();
        if($currentDate > $passwordReset->expiry_date){
            $expiry =  Carbon::now()->addMinutes(10);

        $send_token = ForgotPassword::updateOrCreate([
                'token' => random_int(1000, 9999),
                'email' => $user->email,
                'expiry_date' => $expiry,
            ]);
            $logos = Setting::latest()->first();
        
            Mail::to($user->email)->send(new ResetPasswordEmail($send_token->token, $user, $logos));

            $response =[
                'status' => true,
                'message' => 'Token Expired! Kindly check email for another token'
            ];

            return response()->json($response,200);
        }

       

        $response =[
            "status" => true,
            "message" => "Token Verified Successfully, Please Reset Your Password"
        ];
        return response()->json($response, 200);
    }


    public function passwordReset(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required|min:6',
        ]);
              // Find the email in the password_resets table
            $passwordReset = ForgotPassword::where('email', $request->email)->first();

            if (!$passwordReset) {
                return response()->json(['message' => 'Invalid email.'], 400);
            }

            $user = User::where('email', $passwordReset->email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            // Update the user's password
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the password reset record
        $passwordReset->delete();

        $response = [
            "status" => true,
            "message" => "Password Reset Successfully"
        ];

        return response()->json($response, 200);
    }

}
