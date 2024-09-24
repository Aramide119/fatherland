<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ChatMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'groupId' => 'required',
            'chatUrl' => 'required',
            'chatType' => 'nullable',
            // 'userId' =>'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // $senderId = $request->input('sender_id');
        // $receiverId = $request->input('reciever_id');
    
        // // Check if conversation already exists
        // $chat = Chat::where(function ($query) use ($senderId, $receiverId) {
        //     $query->where('sender_id', $senderId)->where('reciever_id', $receiverId);
        // })->orWhere(function ($query) use ($senderId, $receiverId) {
        //     $query->where('sender_id', $receiverId)->where('reciever_id', $senderId);
        // })->first();

        // //  Check if doesn't conversation exists, if not create new conversation
        // if(!$chat)
        // {
        //     $chat = Chat::create([
        //         'sender_id' => $request->input('sender_id'),
        //         'reciever_id' => $request->input('reciever_id'),
        //     ]);
        // }


        $message = ChatMessage::create([
           'groupId' =>$request->input('groupId'),
            'chatUrl' =>$request->input('chatUrl'),
            'chatType' =>$request->input('chatType'),
            'user_id' => Auth::id(),
            'message' => $request->input('message'),
        ]);

        return response()->json($message, 200);
    }

    public function getChatHistory(Request $request)
    {
        $userId = $request->user()->id;

        $chats = Chat::where('sender_id', $userId)
                 -> orWhere('reciever_id', $userId)
                 ->with(['sender', 'receiver', 'latestMessage'])
                 ->latest()
                 ->get();

            // dd($chats);

        return response()->json($chats, 200);         
    }

    public function getChatMessages(Request $request, $chatId)
    {
        $userId = $request->user()->id;

        $chatMessages = ChatMessage::where('chat_id', $chatId)
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['data' => $chatMessages ], 200);
    }


}
