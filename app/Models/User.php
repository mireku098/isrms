<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'is_active',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Hash password before saving
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->is_active === true;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    /**
     * Get user's role badge class for Bootstrap
     */
    public function getRoleBadgeClass()
    {
        $badges = [
            'storekeeper' => 'bg-primary',
            'principal' => 'bg-success',
            'auditor' => 'bg-info',
            'requester' => 'bg-warning',
            'admin' => 'bg-danger',
        ];

        return $badges[$this->role] ?? 'bg-secondary';
    }

    /**
     * Get readable role name
     */
    public function getRoleDisplayName()
    {
        $names = [
            'storekeeper' => 'Storekeeper',
            'principal' => 'Principal',
            'auditor' => 'Auditor',
            'requester' => 'Requester',
            'admin' => 'Administrator',
        ];

        return $names[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Increment login attempts
     */
    public function incrementLoginAttempts()
    {
        $this->increment('login_attempts');
    }

    /**
     * Reset login attempts
     */
    public function resetLoginAttempts()
    {
        $this->update(['login_attempts' => 0]);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get SRAs created by this user
     */
    public function srasCreated()
    {
        return $this->hasMany(Sra::class, 'created_by');
    }

    /**
     * Get requisitions requested by this user
     */
    public function requisitionsRequested()
    {
        return $this->hasMany(Requisition::class, 'requested_by');
    }

    /**
     * Get requisitions approved by this user
     */
    public function requisitionsApproved()
    {
        return $this->hasMany(Requisition::class, 'approved_by');
    }

    /**
     * Get issues issued by this user
     */
    public function issuesIssued()
    {
        return $this->hasMany(Issue::class, 'issued_by');
    }

    /**
     * Get issues received by this user
     */
    public function issuesReceived()
    {
        return $this->hasMany(Issue::class, 'received_by');
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Get audit logs for this user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
