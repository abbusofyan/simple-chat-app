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
        Log::info("Broadcasting to channel: chat.{$this->conversation->id}");
        return new Channel('chat.' . $this->conversation->id);
    }

    public function broadcastAs()
    {
        return "MessageSent"; // Make sure your frontend listens to this
    }

    public function broadcastWith() {
        return ['message' => $this->chatMessage];
    }
}
