<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index() {
        $conversations = Conversation::where('user_one', auth()->id())->orWhere('user_two', auth()->id())->orderBy('updated_at', 'DESC')->get();
        return Inertia::render('Chat', [
            'conversations' => $conversations
        ]);
    }

    public function room(Conversation $conversation) {
        $conversation->load('messages');
        $conversations = Conversation::where('user_one', auth()->id())->orWhere('user_two', auth()->id())->orderBy('updated_at', 'DESC')->get();
        return Inertia::render('Chat', [
            'conversations' => $conversations,
            'activeChat' => $conversation
        ]);
    }

    public function getConversation(Conversation $conversation) {
        return $conversation;
    }

    public function send(Request $request) {
        $chat = ChatMessage::create($request->all());
        $conversation = Conversation::find($request->conversation_id);
        $conversation->touch();
        broadcast(new MessageSent($chat, $conversation));
        return redirect()->back();
    }
}
