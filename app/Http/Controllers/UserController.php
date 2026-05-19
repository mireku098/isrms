<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $users = User::orderBy('name')->paginate(20);
        
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'roles_count' => User::distinct('role')->count(),
        ];
        
        $roles_overview = User::select('role', \DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        return view('users.index', compact('users', 'stats', 'roles_overview'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('isAdmin');
        
        $roles = ['admin', 'storekeeper', 'auditor', 'principal', 'requester'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,storekeeper,auditor,principal,requester',
            'department' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Log to audit trail
        $this->logAudit('CREATE', 'users', $user->id);

        return redirect()->route('users.show', $user)
            ->with('success', "User '{$user->name}' created successfully.");
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorize('isAdmin');
        
        $auditLogs = $user->auditLogs()->latest()->paginate(10);
        return view('users.show', compact('user', 'auditLogs'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('isAdmin');
        
        $roles = ['admin', 'storekeeper', 'auditor', 'principal', 'requester'];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,storekeeper,auditor,principal,requester',
            'department' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Only allow password change if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        // Log to audit trail
        $this->logAudit('UPDATE', 'users', $user->id);

        return redirect()->route('users.show', $user)
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user from database.
     */
    public function destroy(User $user)
    {
        $this->authorize('isAdmin');
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own user account.');
        }

        $name = $user->name;
        $user->delete();

        // Log to audit trail
        $this->logAudit('DELETE', 'users', $user->id);

        return redirect()->route('users.index')
            ->with('success', "User '{$name}' deleted successfully.");
    }

    /**
     * Toggle user active status
     */
    public function toggle(User $user)
    {
        $this->authorize('isAdmin');
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot deactivate your own user account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        // Log to audit trail
        $this->logAudit('UPDATE', 'users', $user->id);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Log action to audit trail
     */
    private function logAudit($action, $table, $recordId)
    {
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
        ]);
    }
}
