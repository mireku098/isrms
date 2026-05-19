<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;
    public $previousStock;
    public $currentStock;
    public $transactionType;
    public $referenceType;
    public $referenceId;

    /**
     * Create a new event instance.
     */
    public function __construct(
        Item $item,
        int $previousStock,
        int $currentStock,
        string $transactionType,
        string $referenceType,
        int $referenceId
    ) {
        $this->item = $item;
        $this->previousStock = $previousStock;
        $this->currentStock = $currentStock;
        $this->transactionType = $transactionType;
        $this->referenceType = $referenceType;
        $this->referenceId = $referenceId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('inventory-updates'),
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
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'item_code' => $this->item->code,
            'previous_stock' => $this->previousStock,
            'current_stock' => $this->currentStock,
            'transaction_type' => $this->transactionType,
            'reference_type' => $this->referenceType,
            'reference_id' => $this->referenceId,
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
        return 'inventory.updated';
    }
}
