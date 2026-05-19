<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    use HasFactory;

    protected $table = 'inventory_ledger';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'transaction_type',
        'quantity',
        'balance_after',
        'reference_type',
        'reference_id',
        'created_at',
    ];

    protected $dates = ['created_at'];

    /**
     * Get the item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get transaction type display name
     */
    public function getTransactionTypeDisplay()
    {
        return $this->transaction_type === 'RECEIVE' ? 'Received' : 'Issued';
    }

    /**
     * Get reference display
     */
    public function getReferenceDisplay()
    {
        return "{$this->reference_type} #{$this->reference_id}";
    }

    /**
     * Check if this is a receive transaction
     */
    public function isReceive()
    {
        return $this->transaction_type === 'RECEIVE';
    }

    /**
     * Check if this is an issue transaction
     */
    public function isIssue()
    {
        return $this->transaction_type === 'ISSUE';
    }
}
