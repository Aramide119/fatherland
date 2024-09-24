<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Family;
use App\Mail\VerifyEmail;
use App\Models\VerifyUser;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResendVerificationEmail;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "phone_number" => "required|numeric",
            "date_of_birth" => "required|date_format:Y-m-d",
            "password" => "required|min:6",
        ]);

        $user = User::where('email', $request->email)->first();

         // Check if the user already exists and is verified
         $existingUser = User::where('email', $request->email)->whereNotNull('email_verified_at')->first();

         if ($existingUser) {
             return response()->json(['message' => 'This email already exists.'], 400);
         }

         // Check if the user exists but is not verified
         $unverifiedUser = User::where('email', $request->email)->whereNull('email_verified_at')->first();

         if ($unverifiedUser) {
             // Check if the user has a VerifyUser record
             $verifyUser = VerifyUser::where('user_id', $unverifiedUser->id)->first();

             if ($verifyUser) {
                 // Resend the existing verification email with the same token
                 $this->resendVerificationEmail($unverifiedUser, $verifyUser->token);
             } else {
                 // Generate a new token and resend the verification email
                 $this->sendVerificationEmail($unverifiedUser);
             }

             return response()->json(['message' => 'This email already exists, a verification email has been resent.'], 200);
         }

         $dateOfBirth = Carbon::createFromFormat('Y-m-d', $request->date_of_birth);
         $age = $dateOfBirth->diffInYears(Carbon::now());

         if ($age < 13) {
             return response()->json(['message' => 'You are not eligible to sign up.'], 400);
         }

        // Email doesn't exist, create a new user
        $user = $this->createUser([
            "name" => $request->name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "date_of_birth" => $request->date_of_birth,
            "password" => $request->password,
        ]);

        $expiry =  Carbon::now()->addMinutes(5);

        VerifyUser::create([
            'token' => random_int(1000, 9999),
            'user_id' => $user->id,
            'expiry_date' => $expiry,
        ]);

        $logos = Setting::latest()->first();

        Mail::to($user->email)->send(new VerifyEmail($user, $logos));

        return response()->json([
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'date_of_birth' => $user->date_of_birth,
                'phone_number' => $user->phone_number,
            ],
            'status' => true,
            'message' => 'User registered successfully. Please check your email to verify your account.',
        ], 200);
    }

   private function resendVerificationEmail(User $user, $token)
    {
        // Check if the user has a VerifyUser record
        $verifyUser = VerifyUser::where('user_id', $user->id)->first();

        // If there is no VerifyUser record, return false
        if (!$verifyUser) {
            return false;
        }

        $logos = Setting::latest()->first();

        // Resend the existing verification email with the same token
        Mail::to($user->email)->send(new ResendVerificationEmail($user, $verifyUser->token, $logos));

        return true;
    }


    protected function sendVerificationEmail($user)
    {
        try {
            $expiry =  Carbon::now()->addMinutes(5);
            // Generate a new VerifyUser record
            VerifyUser::updateOrCreate([
                'token' => random_int(1000, 9999),
                'user_id' => $user->id,
                'expiry_date' => $expiry
            ]);

            $logos = Setting::latest()->first();

            // Send the verification email
            Mail::to($user->email)->send(new VerifyEmail($user, $logos));

            return true;
        } catch (\Exception $e) {
            // Handle any exception, log, or return false based on your requirements
            return false;
        }
    }



    public function login(LoginRequest $request){
        $attributes = $request->validated();

         //check for the user email
         $user = User::where('email', $attributes['email'])->first();

        if(!$user){
            $response = [
                'status' => false,
                'message' => 'Email does not exist'
            ];

            return response()->json($response, 404);
        }

         if($user->email_verified_at == NULL)
         {
            $response=[
                'status' => false,
                'message' => 'Please verify your email'
            ];

            return response()->json($response, 400);
         }
        //  dd(Hash::check($attributes['password'], $user->password));
        if(!Hash::check($attributes['password'], $user->password))
        {
            $response=[
                'status' => false,
                'message' => 'Credentials do not match our system'
            ];

            return response()->json($response, 422);
        }

        //create Token
        $token = $user->createToken('LaravelAuthApp')->accessToken;

        $response=[
            'data' => $user,
            'token' => $token,
            'message' => 'Login Succesfully'
        ];

        return response()->json($response, 200);

    }

    public function verifyEmail(Request $request)
    {
        // Retrieve the verification token from the request
        $token = $request->input('token');

        // Find the corresponding VerifyUser record with the provided token
        $verifyUser = VerifyUser::where('token', $token)->first();

        // Check if a matching VerifyUser record is found
        if ($verifyUser) {
            // Get the associated user model
            $user = $verifyUser->user;

            // Check if token has expired
            $currentDate = Carbon::now();
            if ($currentDate > $verifyUser->expiry_date) {
                $expiry = Carbon::now()->addMinutes(5);

                VerifyUser::updateOrCreate([
                    'token' => random_int(1000, 9999),
                    'user_id' => $user->id,
                    'expiry_date' => $expiry,
                ]);

                $logos = Setting::latest()->first();

                // Send email to user
                Mail::to($user->email)->send(new VerifyEmail($user, $logos));

                $response = [
                    'status' => true,
                    'message' => 'Token Expired! Kindly check email for another token',
                ];

                return response()->json($response, 401);
            }

            // Mark the user as verified
            $user->email_verified_at = now();
            $user->save();

            // Delete the VerifyUser record
            VerifyUser::where('user_id', $verifyUser->user_id)->delete();

            // Generate a new access token for the user
            $token = $user->createToken('LaravelAuthApp')->accessToken;

            // Return a response that shows that this user has been verified
            $response = [
                'data' => $user,
                'token' => $token,
                'message' => 'User verified successfully.',
            ];

            return response()->json($response, 200);
        }

        // If no matching VerifyUser record is found, return an error response
        return response()->json([
            'message' => 'Invalid verification token.',
        ], 400);
    }


    public function logout()
    {
        $id = Auth::id();

        Auth::user()->tokens()->where('user_id', $id)->delete();

        $response=[
            "status" => true,
            "message" => "Successfully Logged Out"
        ];

        return response()->json($response, 200);

    }


protected function createUser(array $data)
{
    // Create a new user
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone_number' => $data['phone_number'],
        'date_of_birth' => $data['date_of_birth'],
        'password' => Hash::make($data['password']),
    ]);

     // Check if the user came from an invite link and attach them to the family
     if (isset($data['family_token'])) {
        $family = Family::where('invite_token', $data['family_token'])->first();

        if ($family) {
            $family->members()->attach($user->id, ['created_at' => now()]);
            $family->users()->attach($user->id, ['created_at' => now()]);
        }
    }

    return $user;
}

public function blockUser(Request $request , $id)
{
    $block_user = User::findOrFail($id);
    $user = Auth::user();

    $existingBlockedUser= BlockedUser::where('blocked_user_id', $block_user->id)
    ->where('user_id', $user->id)->first();

    if($existingBlockedUser) {
        //If the user has already blocked this user , unblock it.
        $existingBlockedUser->delete();

        return response()->json(['message' => 'you have successfully unblocked '.$block_user->name]);

    }
    if($user->id != $id )
    {

    $blockedUser = BlockedUser::create([
        'user_id' => $user->id,
        'blocked_user_id' =>$block_user->id
    ]);

    $response = [
        'message' => 'You have successfully blocked '.$block_user->name,
        'data' => $blockedUser,
    ];
    return response()->json($response, 200 );
    }
    else{
        return response()->json(['message' => 'Methods not allowed', 405]);

    }


}

public function getBlockedUser(Request $request)
{
    $blockedUsers = auth()->user()->blockedusers()->get();

    $response = [
        'blocked_Users' => $blockedUsers,
    ];

    return response()->json($response, 200 );
}

}
