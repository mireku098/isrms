<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'category_id',
        'unit',
        'min_stock',
        'max_stock',
    ];

    /**
     * Get category for this item
     */
    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get SRA items for this item
     */
    public function sraItems()
    {
        return $this->hasMany(SraItem::class);
    }

    /**
     * Get requisition items for this item
     */
    public function requisitionItems()
    {
        return $this->hasMany(RequisitionItem::class);
    }

    /**
     * Get issue items for this item
     */
    public function issueItems()
    {
        return $this->hasMany(IssueItem::class);
    }

    /**
     * Get inventory ledger entries for this item
     */
    public function ledgerEntries()
    {
        return $this->hasMany(InventoryLedger::class);
    }

    /**
     * Get current stock level
     */
    public function getCurrentStock()
    {
        $ledger = $this->ledgerEntries()->latest()->first();
        return $ledger ? $ledger->balance_after : 0;
    }

    /**
     * Check if stock is below minimum
     */
    public function isLowStock()
    {
        return $this->getCurrentStock() < $this->min_stock;
    }

    /**
     * Check if stock is above maximum
     */
    public function isOverStock()
    {
        return $this->getCurrentStock() > $this->max_stock;
    }
}
