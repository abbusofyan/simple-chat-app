<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Conversation;

class ContactController extends Controller
{
    public function index() {
        $contacts = User::where('id', '!=', auth()->user()->id)->get();
        return Inertia::render('Contact', [
            'contacts' => $contacts
        ]);
    }

    public function startChat(User $contact) {
        $conversation = Conversation::with('messages')->where(function ($query) {
            $query->where('user_one', auth()->id())
                  ->orWhere('user_two', auth()->id());
        })
        ->where(function ($query) use ($contact) {
            $query->where('user_one', $contact->id)
                  ->orWhere('user_two', $contact->id);
        })
        ->first();
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one' => auth()->user()->id,
                'user_two' => $contact->id
            ]);
        }
        return redirect('/chat/room/' . $conversation->id);
    }
}
