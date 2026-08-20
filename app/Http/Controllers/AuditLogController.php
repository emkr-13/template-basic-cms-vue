<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(Request $request): Response
    {
        $query = ActivityLog::query()->with('user')->latest('id');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search): void {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        $logs = $query->paginate(20)->withQueryString()->through(fn (ActivityLog $log): array => [
            'id' => $log->id,
            'action' => $log->action,
            'description' => $log->description,
            'subject_type' => $log->subject_type ? class_basename($log->subject_type) : null,
            'subject_id' => $log->subject_id,
            'properties' => $log->properties,
            'ip_address' => $log->ip_address,
            'user' => $log->user ? [
                'id' => $log->user->id,
                'name' => $log->user->name,
                'email' => $log->user->email,
                'avatar_url' => $log->user->avatar_url,
            ] : null,
            'created_at' => $log->created_at->format('d M Y H:i:s'),
            'created_at_human' => $log->created_at->diffForHumans(),
        ]);

        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'unique_users' => ActivityLog::distinct('user_id')->count('user_id'),
            'auth_logs' => ActivityLog::where('action', 'like', 'auth.%')->count(),
        ];

        return Inertia::render('AuditLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only('search', 'action'),
            'stats' => $stats,
        ]);
    }
}
