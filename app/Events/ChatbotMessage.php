<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatbotMessage implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public string $message;
    public string $sender;

    /**
     * Create a new event instance.
     */
    public function __construct(string $message, string $sender = 'bot')
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * Broadcast on a public chatbot channel.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('puffcart-chatbot');
    }

    /**
     * Event name for JavaScript Echo listener.
     */
    public function broadcastAs(): string
    {
        return 'chatbot.message';
    }
}