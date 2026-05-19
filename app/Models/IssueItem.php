<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueItem extends Model
{
    use HasFactory;

    protected $table = 'issue_items';
    public $timestamps = false;

    protected $fillable = [
        'issue_id',
        'item_id',
        'quantity_issued',
    ];

    /**
     * Get the issue
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
