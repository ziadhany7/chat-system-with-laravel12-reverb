<?php

namespace App\Http\Controllers;

use App\Models\{Message, User};
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index() {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('dashboard', [ 'users' => $users]);
    }

   public function chatWith(User $user) {
        $messages = Message::where(function($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($user) {
            // تأكد أن هنا $user->id وليس $user->id()
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->get();

        return response()->json($messages);
    }

    public function send(Request $request) {
        try {
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'content' => $request->message,
                'is_broadcast' => $request->is_broadcast ?? false
            ]);

            $message->load('sender');

            // هنا نختبر الربط
            broadcast(new MessageSent($message))->toOthers();

            return $message;
        } catch (\Exception $e) {
            // هذا السطر سيجعل الخطأ يظهر في الكونسول بدلاً من صفحة 500 غامضة
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // جلب رسائل البرودكاست
    public function getBroadcastMessages() {
        $messages = Message::where('is_broadcast', true)
                        ->with('sender')
                        ->orderBy('created_at', 'asc')
                        ->get();
        return response()->json($messages);
    }
}
