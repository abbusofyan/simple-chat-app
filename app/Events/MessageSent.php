<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage, $conversation;
    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $chatMessage, Conversation $conversation)
    {
        $chatMessage->load('sender');
        $this->chatMessage = $chatMessage;
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return [
            new Channel('chat.' . $this->conversation->id),
            new Channel('notification.' . $this->chatMessage->receiver_id), //send notification to the receiver
        ];
        // return new Channel('chat.' . $this->conversation->id);
    }

    public function broadcastAs()
    {
        return "MessageSent";
    }

    public function broadcastWith() {
        return ['message' => $this->chatMessage];
    }
}
