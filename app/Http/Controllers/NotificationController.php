<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notifications() 
    {

        $userId = Auth::id();
        $user=User::where('id',$userId)->first();
        $notifications = $user->notifications()
        ->with('sender','post','family','comment','dynasty')
        ->get();

        return response()->json(['notifications' => $notifications], 200);
    }
}
