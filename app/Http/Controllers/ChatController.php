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
        $conversation->messages = json_decode(json_encode([]));
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
        return response()->json(['status' => true]);
    }

    public function getConversationMessages(Conversation $conversation) {
        $beforeId = request('before');
        $query = $conversation->messages()->orderBy('id', 'DESC');
        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        $messages = $query->limit(5)->get()->sortBy('id')->values();
        return response()->json(['messages' => $messages]);
    }
}
