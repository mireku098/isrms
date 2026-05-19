<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display user's notifications
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);
        
        $unreadCount = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $count = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();
        
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Get recent unread notifications (for dropdown)
     */
    public function recent()
    {
        $notifications = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Verify user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Notification $notification)
    {
        // Verify user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsUnread();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        // Verify user owns this notification
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'All notifications deleted.');
    }

    /**
     * Create notification (Admin function)
     */
    public function create(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Notification::create($validated);

        return back()->with('success', 'Notification sent.');
    }

    /**
     * Broadcast notification to multiple users
     */
    public function broadcast(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'role' => 'nullable|in:admin,storekeeper,auditor,principal,requester',
            'message' => 'required|string|max:1000',
        ]);

        $users = $validated['role'] 
            ? \App\Models\User::where('role', $validated['role'])->get()
            : \App\Models\User::all();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => $validated['message'],
            ]);
        }

        return back()->with('success', 'Notification broadcasted to ' . count($users) . ' users.');
    }
}
