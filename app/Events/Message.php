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

    public function __construct(string $content, string $user)
    {
        $this->content = $content;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return ['messages'];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}