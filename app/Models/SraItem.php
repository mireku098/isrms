<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SraItem extends Model
{
    use HasFactory;

    protected $table = 'sra_items';
    public $timestamps = false;

    protected $fillable = [
        'sra_id',
        'item_id',
        'quantity',
    ];

    /**
     * Get the SRA
     */
    public function sra()
    {
        return $this->belongsTo(Sra::class);
    }

    /**
     * Get the item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
