<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendLikeEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $likeStr;
    private int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $likeStr, int $userId)
    {
        $this->likeStr = $likeStr;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('channel-name'),
            // new Channel('send_like_' . $this->userId),
            new PrivateChannel('send_like_' . $this->userId),
        ];
    }

    /**
     * Имя вещания события.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'send_like';
    }

    /**
     * Получите данные в вещание.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'like_str' => $this->likeStr,
        ];
    }
}
