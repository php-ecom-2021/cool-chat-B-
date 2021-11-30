<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $content;
    public $user;
    public $channelID;

    public function __construct(int $channelID, string $content, string $user)
    {
        $this->content = $content;
        $this->user = $user;
        $this->channelID = $channelID;
        
    }

    public function broadcastOn()
    {
        return ['messages', 'messages'.$this->channelID];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}