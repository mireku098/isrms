<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sra;
use App\Models\Requisition;
use App\Models\Issue;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect user to their role-specific dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return $this->admin();
        } elseif ($user->hasRole('storekeeper')) {
            return $this->storekeeper();
        } elseif ($user->hasRole('principal')) {
            return $this->principal();
        } elseif ($user->hasRole('auditor')) {
            return $this->auditor();
        } else {
            return $this->requester();
        }
    }

    /**
     * Admin Dashboard
     */
    public function admin()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_items' => Item::count(),
            'total_requisitions' => Requisition::count(),
            'pending_requisitions' => Requisition::where('status', 'pending')->count(),
            'approved_requisitions' => Requisition::where('status', 'approved')->count(),
            'total_sras' => Sra::count(),
            'total_issues' => Issue::count(),
            'recent_logs' => AuditLog::with('user')->latest()->take(5)->get(),
            'recent_requisitions' => Requisition::with('requester', 'approver')
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    /**
     * Storekeeper Dashboard
     */
    public function storekeeper()
    {
        $stats = [
            'pending_sras' => Sra::where('status', 'pending')->count(),
            'approved_requisitions' => Requisition::where('status', 'approved')
                ->whereDoesntHave('issues')
                ->count(),
            'low_stock_items' => Item::all()->filter(function($item) {
                return $item->isLowStock();
            })->count(),
            'total_items' => Item::count(),
            'recent_sras' => Sra::latest()->take(5)->get(),
            'pending_tasks' => $this->getStorekeeperTasks(),
        ];

        return view('dashboard.storekeeper', compact('stats'));
    }

    /**
     * Principal Dashboard
     */
    public function principal()
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'pending_requisitions' => Requisition::where('status', 'pending')->count(),
            'pending_sras' => Sra::where('status', 'pending')
                ->where('signed_storekeeper', true)
                ->where('signed_auditor', true)
                ->where('signed_principal', false)
                ->count(),
            'total_requisitions' => Requisition::count(),
            'pending_requisition_list' => Requisition::where('status', 'pending')
                ->with('requester', 'requisitionItems')
                ->latest()
                ->take(5)
                ->get(),
            'recent_requisitions' => Requisition::where('status', '!=', 'pending')
                ->with('requester', 'approver', 'requisitionItems')
                ->latest()
                ->take(5)
                ->get(),
            'pending_sra_list' => Sra::where('status', 'pending')
                ->where('signed_storekeeper', true)
                ->where('signed_auditor', true)
                ->where('signed_principal', false)
                ->with('sraItems')
                ->latest()
                ->take(5)
                ->get(),
            'approvals_today' => Requisition::where('status', 'approved')
                ->where('updated_at', '>=', $today)
                ->count() + 
                Sra::where('signed_principal', true)
                ->where('updated_at', '>=', $today)
                ->count(),
            'approvals_this_week' => Requisition::where('status', 'approved')
                ->where('updated_at', '>=', $thisWeek)
                ->count() + 
                Sra::where('signed_principal', true)
                ->where('updated_at', '>=', $thisWeek)
                ->count(),
            'approvals_this_month' => Requisition::where('status', 'approved')
                ->where('updated_at', '>=', $thisMonth)
                ->count() + 
                Sra::where('signed_principal', true)
                ->where('updated_at', '>=', $thisMonth)
                ->count(),
        ];

        // Calculate approval rate
        $totalReqs = Requisition::count();
        $approvedReqs = Requisition::where('status', 'approved')->count();
        $stats['approval_rate'] = $totalReqs > 0 ? round(($approvedReqs / $totalReqs) * 100, 1) : 0;

        return view('dashboard.principal', compact('stats'));
    }

    /**
     * Auditor Dashboard
     */
    public function auditor()
    {
        $thisMonth = now()->startOfMonth();
        
        $stats = [
            'pending_verifications' => Sra::where('status', 'pending')
                ->where('signed_storekeeper', true)
                ->where('signed_auditor', false)
                ->count(),
            'total_sras' => Sra::count(),
            'recent_sras' => Sra::with('createdBy')->latest()->take(5)->get(),
            'verified_this_month' => Sra::where('signed_auditor', true)
                ->where('updated_at', '>=', $thisMonth)
                ->count(),
            'recent_requisitions' => Requisition::where('status', '!=', 'pending')
                ->with('requester', 'approver', 'requisitionItems')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.auditor', compact('stats'));
    }

    /**
     * Requester Dashboard
     */
    public function requester()
    {
        $user = auth()->user();
        $stats = [
            'pending_requisitions' => Requisition::where('requested_by', $user->id)
                ->where('status', 'pending')
                ->count(),
            'approved_requisitions' => Requisition::where('requested_by', $user->id)
                ->where('status', 'approved')
                ->count(),
            'issued_requisitions' => Requisition::where('requested_by', $user->id)
                ->whereHas('issues')
                ->count(),
            'rejected_requisitions' => Requisition::where('requested_by', $user->id)
                ->where('status', 'rejected')
                ->count(),
            'recent_my_requisitions' => Requisition::where('requested_by', $user->id)
                ->with('requisitionItems')
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('dashboard.requester', compact('stats'));
    }

    /**
     * Helper to get tasks for storekeeper
     */
    private function getStorekeeperTasks()
    {
        $tasks = [];

        // SRAs needing storekeeper signature
        $pendingSras = Sra::where('status', 'pending')
            ->where('signed_storekeeper', false)
            ->get();
        
        foreach ($pendingSras as $sra) {
            $tasks[] = [
                'title' => "Sign SRA #{$sra->sra_number}",
                'desc' => "From {$sra->supplier_details}",
                'badge' => 'Action Required',
                'badge_class' => 'bg-warning',
                'link' => route('sra.show', $sra)
            ];
        }

        // Approved requisitions needing issues
        $approvedReqs = Requisition::where('status', 'approved')
            ->whereDoesntHave('issues')
            ->with('requester')
            ->get();
        
        foreach ($approvedReqs as $req) {
            $tasks[] = [
                'title' => "Issue Items for {$req->requisition_number}",
                'desc' => "Requested by " . ($req->requester ? $req->requester->name : 'Unknown'),
                'badge' => 'New',
                'badge_class' => 'bg-primary',
                'link' => route('requisitions.show', $req)
            ];
        }

        return collect($tasks)->take(5);
    }
}
