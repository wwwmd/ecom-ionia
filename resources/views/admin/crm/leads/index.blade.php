@extends('layouts.admin')
@section('title', 'CRM Leads')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <span>CRM</span>
    <i class="bi bi-chevron-right"></i><span>Leads</span>
@endsection

@section('content')

<div class="z-page-head">
    <div>
        <h1>Leads</h1>
        <p>Manage sales leads and conversions</p>
    </div>
    <div class="z-page-head-actions">
        <a href="{{ route('admin.leads.create') }}" class="z-btn-primary"><i class="bi bi-plus-lg"></i> Add Lead</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="z-mini-stat">
            <div class="z-ms-icon z-ms-blue"><i class="bi bi-funnel"></i></div>
            <div><span>Total</span><strong>{{ $stats['total'] }}</strong></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-mini-stat">
            <div class="z-ms-icon z-ms-teal"><i class="bi bi-stars"></i></div>
            <div><span>New</span><strong>{{ $stats['new'] }}</strong></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-mini-stat">
            <div class="z-ms-icon z-ms-orange"><i class="bi bi-telephone"></i></div>
            <div><span>Contacted</span><strong>{{ $stats['contacted'] }}</strong></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-mini-stat">
            <div class="z-ms-icon z-ms-green"><i class="bi bi-trophy"></i></div>
            <div><span>Converted</span><strong>{{ $stats['converted'] }}</strong></div>
        </div>
    </div>
</div>

<div class="z-card mb-3">
    <form method="GET" class="z-filters">
        <div class="z-fi">
            <label>Status</label>
            <select name="status" class="z-select">
                <option value="">All</option>
                @foreach(['new','contacted','interested','followup','negotiation','converted','lost'] as $s)
                    <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="z-fi">
            <label>Priority</label>
            <select name="priority" class="z-select">
                <option value="">All</option>
                @foreach(['low','medium','high','urgent'] as $p)
                    <option value="{{ $p }}" @selected(request('priority') == $p)>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="z-fi z-fi-btn">
            <button class="z-btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('admin.leads') }}" class="z-btn-ghost">Reset</a>
        </div>
    </form>
</div>

<div class="z-card">
    <div class="table-responsive">
        <table class="table z-table">
            <thead>
                <tr>
                    <th>Code</th><th>Lead</th><th>Phone</th><th>Source</th>
                    <th>Priority</th><th>Status</th><th>Agent</th><th></th>
                </tr>
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
                    <td>{{ ucfirst($lead->source) }}</td>
                    <td><span class="z-priority z-priority-{{ $lead->priority }}">{{ $lead->priority }}</span></td>
                    <td><span class="z-status z-status-{{ $lead->status }}">{{ ucfirst($lead->status) }}</span></td>
                    <td>{{ $lead->agent->name ?? 'Unassigned' }}</td>
                    <td>
                        <a href="{{ route('admin.leads.show', $lead) }}" class="z-act-btn"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No leads yet</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $leads->links() }}
    </div>
</div>

@endsection
