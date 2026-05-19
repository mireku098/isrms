<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs (Admin only)
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $logs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('audit-logs.index', compact('logs'));
    }

    /**
     * Filter audit logs by user
     */
    public function byUser(User $user)
    {
        $this->authorize('isAdmin');
        
        $logs = $user->auditLogs()
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('audit-logs.by-user', compact('user', 'logs'));
    }

    /**
     * Filter audit logs by action
     */
    public function byAction($action)
    {
        $this->authorize('isAdmin');
        
        $validActions = ['CREATE', 'UPDATE', 'DELETE', 'APPROVE', 'REJECT', 'SIGN', 'ISSUE'];
        
        if (!in_array($action, $validActions)) {
            return back()->with('error', 'Invalid action.');
        }

        $logs = AuditLog::where('action', $action)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('audit-logs.by-action', compact('logs', 'action'));
    }

    /**
     * Filter audit logs by table
     */
    public function byTable($table)
    {
        $this->authorize('isAdmin');
        
        $validTables = ['users', 'items', 'sra', 'requisitions', 'issues', 'inventory_ledger'];
        
        if (!in_array($table, $validTables)) {
            return back()->with('error', 'Invalid table.');
        }

        $logs = AuditLog::where('table_name', $table)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('audit-logs.by-table', compact('logs', 'table'));
    }

    /**
     * Filter audit logs by date range
     */
    public function byDateRange(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $logs = AuditLog::whereBetween('created_at', [
            $validated['from'] . ' 00:00:00',
            $validated['to'] . ' 23:59:59',
        ])
        ->with('user')
        ->orderByDesc('created_at')
        ->paginate(50);
        
        return view('audit-logs.by-date-range', compact('logs', 'validated'));
    }

    /**
     * Get statistics
     */
    public function statistics()
    {
        $this->authorize('isAdmin');
        
        $totalLogs = AuditLog::count();
        
        $actionStats = AuditLog::selectRaw('action, count(*) as count')
            ->groupBy('action')
            ->get()
            ->pluck('count', 'action');
        
        $tableStats = AuditLog::selectRaw('table_name, count(*) as count')
            ->groupBy('table_name')
            ->get()
            ->pluck('count', 'table_name');
        
        $userStats = AuditLog::selectRaw('user_id, count(*) as count')
            ->with('user')
            ->groupBy('user_id')
            ->orderByRaw('count desc')
            ->limit(10)
            ->get();
        
        $thisMonthLogs = AuditLog::whereMonth('created_at', now()->month)->count();
        $todayLogs = AuditLog::whereDate('created_at', today())->count();
        
        return view('audit-logs.statistics', compact(
            'totalLogs',
            'actionStats',
            'tableStats',
            'userStats',
            'thisMonthLogs',
            'todayLogs'
        ));
    }

    /**
     * Export audit logs to CSV
     */
    public function export(Request $request)
    {
        $this->authorize('isAdmin');
        
        $query = AuditLog::with('user');
        
        // Apply filters if provided
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->has('table_name')) {
            $query->where('table_name', $request->table_name);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $logs = $query->orderByDesc('created_at')->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="audit-logs-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Date', 'User', 'Action', 'Table', 'Record ID']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    ($log->user ? $log->user->name : 'System'),
                    $log->action,
                    $log->table_name,
                    $log->record_id ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get recent activity
     */
    public function recent()
    {
        $this->authorize('isAdmin');
        
        $logs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
        
        return view('audit-logs.recent', compact('logs'));
    }

    /**
     * Compliance report - Changes summary
     */
    public function complianceReport()
    {
        $this->authorize('isAdmin');
        
        $period = request('period', '7'); // days
        $date = now()->subDays((int)$period);
        
        $creates = AuditLog::where('action', 'CREATE')
            ->where('created_at', '>=', $date)
            ->count();
        
        $updates = AuditLog::where('action', 'UPDATE')
            ->where('created_at', '>=', $date)
            ->count();
        
        $deletes = AuditLog::where('action', 'DELETE')
            ->where('created_at', '>=', $date)
            ->count();
        
        $approvals = AuditLog::where('action', 'APPROVE')
            ->where('created_at', '>=', $date)
            ->count();
        
        $recentLogs = AuditLog::with('user')
            ->where('created_at', '>=', $date)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();
        
        return view('audit-logs.compliance-report', compact(
            'creates',
            'updates',
            'deletes',
            'approvals',
            'recentLogs',
            'period'
        ));
    }
}
