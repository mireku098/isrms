<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'issued_by',
        'received_by',
        'receiver_name',
        'receiver_signature',
        'remarks',
        'comments',
        'received_at',
    ];

    /**
     * Get the requisition
     */
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    /**
     * Get the user who issued items
     */
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the user who received items
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get issue items
     */
    public function issueItems()
    {
        return $this->hasMany(IssueItem::class);
    }

    /**
     * Get items through issue items
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'issue_items')
                    ->withPivot('quantity_issued');
    }

    /**
     * Get total items issued
     */
    public function getTotalIssued()
    {
        return $this->issueItems()->sum('quantity_issued');
    }
}
