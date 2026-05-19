<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'created_at',
    ];

    protected $dates = ['created_at'];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action display name
     */
    public function getActionDisplay()
    {
        $actions = [
            'CREATE' => 'Created',
            'UPDATE' => 'Updated',
            'DELETE' => 'Deleted',
            'APPROVE' => 'Approved',
            'REJECT' => 'Rejected',
            'SIGN' => 'Signed',
            'ISSUE' => 'Issued',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Get full description
     */
    public function getDescription()
    {
        return "{$this->user->name} {$this->getActionDisplay()} record #{$this->record_id} in {$this->table_name}";
    }
}
