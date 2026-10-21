@extends('layouts.admin')
@section('title', 'My Leads')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>My Leads</span>
@endsection

@section('content')

<div class="z-page-head">
    <div>
        <h1>My Leads</h1>
        <p>{{ $leads->total() }} leads assigned to you</p>
    </div>
</div>

<div class="z-card">
    <div class="table-responsive">
        <table class="table z-table">
            <thead>
                <tr><th>Code</th><th>Lead</th><th>Phone</th><th>Priority</th><th>Status</th><th>Follow-up</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($leads as $lead)
                <tr>
                    <td><strong>{{ $lead->lead_code }}</strong></td>
                    <td>
                        <div class="z-user-cell">
                            <div class="z-uc-avatar">{{ strtoupper(substr($lead->name,0,1)) }}</div>
                            <div><strong>{{ $lead->name }}</strong><small>{{ $lead->email }}</small></div>
                        </div>
                    </td>
                    <td>{{ $lead->phone }}</td>
                    <td><span class="z-priority z-priority-{{ $lead->priority }}">{{ $lead->priority }}</span></td>
                    <td><span class="z-status z-status-{{ $lead->status }}">{{ ucfirst($lead->status) }}</span></td>
                    <td>{{ $lead->followup_date ? $lead->followup_date->format('d M') : '—' }}</td>
                    <td><a href="{{ route('agent.leads.show', $lead) }}" class="z-act-btn"><i class="bi bi-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No leads assigned</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $leads->links() }}
    </div>
</div>

@endsection
