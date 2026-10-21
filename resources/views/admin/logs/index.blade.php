@extends('layouts.admin')
@section('title', 'Activity Logs')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Activity Logs</span>
@endsection

@section('content')

<div class="z-page-head">
    <h1>Activity Logs</h1>
</div>

<div class="z-card">
    <div class="table-responsive">
        <table class="table z-table">
            <thead>
                <tr><th>Time</th><th>User</th><th>Module</th><th>Action</th><th>Description</th><th>IP</th></tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td><small>{{ $log->created_at->format('d M Y, h:i A') }}</small></td>
                    <td>
                        @if($log->user)
                            <strong>{{ $log->user->name }}</strong>
                            <small class="d-block text-muted">{{ $log->user_type }}</small>
                        @else
                            <span class="text-muted">System</span>
                        @endif
                    </td>
                    <td><span class="z-status">{{ $log->module }}</span></td>
                    <td>{{ $log->action }}</td>
                    <td><small>{{ $log->description }}</small></td>
                    <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No logs yet</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>

@endsection
