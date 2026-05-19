<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'requested_by',
        'approved_by',
        'department',
        'request_date',
        'purpose',
        'status',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    /**
     * Get the requester
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the approver
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get requisition items
     */
    public function requisitionItems()
    {
        return $this->hasMany(RequisitionItem::class);
    }

    /**
     * Get items through requisition items
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'requisition_items')
                    ->withPivot('quantity_requested');
    }

    /**
     * Get related issues
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Check if requisition is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Get total items count
     */
    public function getTotalItems()
    {
        return $this->requisitionItems()->sum('quantity_requested');
    }

    /**
     * Get the formatted requisition number
     */
    public function getRequisitionNumberAttribute()
    {
        return $this->requisition_id ?? ('REQ-' . str_pad($this->id, 5, '0', STR_PAD_LEFT));
    }
}
