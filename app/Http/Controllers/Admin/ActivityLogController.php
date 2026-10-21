<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->when(request('module'), fn ($q, $m) => $q->where('module', $m))
            ->when(request('user_type'), fn ($q, $t) => $q->where('user_type', $t))
            ->latest()
            ->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }
}
