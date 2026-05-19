<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.activity-logs', compact('logs'));
    }

    private function authorizeAdmin()
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && $user->name !== 'Administrateur') {
            abort(403, 'Action non autorisée.');
        }
    }
}
