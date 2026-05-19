<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sra extends Model
{
    use HasFactory;

    protected $table = 'sra';

    protected $fillable = [
        'sra_number',
        'supplier_details',
        'created_by',
        'status',
        'signed_storekeeper',
        'signed_auditor',
        'signed_principal',
    ];

    protected $casts = [
        'signed_storekeeper' => 'boolean',
        'signed_auditor' => 'boolean',
        'signed_principal' => 'boolean',
    ];

    /**
     * Get the user who created this SRA
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get SRA items
     */
    public function sraItems()
    {
        return $this->hasMany(SraItem::class);
    }

    /**
     * Get items through SRA items
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'sra_items')
                    ->withPivot('quantity');
    }

    /**
     * Check if SRA is fully approved (by Auditor and Principal only)
     * Storekeeper only creates, does not approve
     */
    public function isFullySigned()
    {
        return $this->signed_auditor && 
               $this->signed_principal;
    }

    /**
     * Get approval status
     */
    public function getApprovalStatus()
    {
        $statuses = [];
        $statuses['Auditor'] = $this->signed_auditor ? '✓' : '✗';
        $statuses['Principal'] = $this->signed_principal ? '✓' : '✗';
        return $statuses;
    }
}
