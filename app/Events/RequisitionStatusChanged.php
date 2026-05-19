<?php

namespace App\Events;

use App\Models\Requisition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequisitionStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requisition;
    public $previousStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Requisition $requisition, string $previousStatus, string $newStatus)
    {
        $this->requisition = $requisition;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('requisition-updates'),
            new Channel('user-' . $this->requisition->requested_by),
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
            'requisition_number' => $this->requisition->requisition_number,
            'department' => $this->requisition->department,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
            'requested_by' => $this->requisition->requested_by,
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
        return 'requisition.status-changed';
    }
}
