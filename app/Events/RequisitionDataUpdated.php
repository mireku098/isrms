<?php

namespace App\Events;

use App\Models\Requisition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequisitionDataUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requisition;
    public $itemsData;

    /**
     * Create a new event instance.
     */
    public function __construct(Requisition $requisition, array $itemsData)
    {
        $this->requisition = $requisition;
        $this->itemsData = $itemsData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('requisition-' . $this->requisition->id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'requisition_id' => $this->requisition->id,
            'items' => $this->itemsData,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get the name of the event to broadcast as.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'requisition.data-updated';
    }
}
