@extends('layouts.admin')
@section('title', 'Agent Dashboard')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Dashboard</span>
@endsection

@section('content')

<div class="z-page-head">
    <div>
        <h1>Hello {{ auth()->user()->name }} 👋</h1>
        <p>You have <strong>{{ $stats['followups'] }}</strong> follow-ups today</p>
    </div>
    <div class="z-page-head-actions">
        <a href="{{ route('agent.leads') }}" class="z-btn-primary"><i class="bi bi-funnel"></i> My Leads</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-blue">
            <div class="z-kpi-top"><div class="z-kpi-icon"><i class="bi bi-funnel"></i></div></div>
            <div class="z-kpi-value">{{ $stats['leads'] }}</div>
            <div class="z-kpi-label">Assigned Leads</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-orange">
            <div class="z-kpi-top"><div class="z-kpi-icon"><i class="bi bi-calendar-check"></i></div></div>
            <div class="z-kpi-value">{{ $stats['followups'] }}</div>
            <div class="z-kpi-label">Today Follow-ups</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-teal">
            <div class="z-kpi-top"><div class="z-kpi-icon"><i class="bi bi-trophy"></i></div></div>
            <div class="z-kpi-value">{{ $stats['converted'] }}</div>
            <div class="z-kpi-label">Converted</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-purple">
            <div class="z-kpi-top"><div class="z-kpi-icon"><i class="bi bi-cash-coin"></i></div></div>
            <div class="z-kpi-value">₹{{ number_format($stats['commission'], 0) }}</div>
            <div class="z-kpi-label">Commission</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="z-card">
            <div class="z-card-head">
                <h5>Today's Follow-ups</h5>
                <a href="{{ route('agent.followups') }}" class="z-link">View All</a>
            </div>
            @forelse($todayFollowups as $f)
                <div style="display:flex;align-items:center;gap:14px;padding:14px;border-radius:10px;border-bottom:1px solid var(--z-border)">
                    <div style="width:60px;font-weight:700;color:var(--z-blue);font-size:14px">
                        {{ $f->scheduled_at->format('h:i A') }}
                    </div>
                    <div style="flex:1">
                        <strong style="display:block;font-size:14px">{{ $f->lead->name ?? '—' }}</strong>
                        <small style="color:var(--z-muted)"><i class="bi bi-{{ ['telephone','whatsapp','envelope','people','bell'][array_search($f->type, ['call','whatsapp','email','meeting','other'])] ?? 'bell' }}"></i> {{ ucfirst($f->type) }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4 mb-0">No follow-ups today</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-5">
        <div class="z-card">
            <div class="z-card-head">
                <h5>Recent Leads</h5>
                <a href="{{ route('agent.leads') }}" class="z-link">View All</a>
            </div>
            @forelse($recentLeads as $l)
                <div class="z-lead-item">
                    <div class="z-li-avatar">{{ strtoupper(substr($l->name,0,1)) }}</div>
                    <div class="z-li-info">
                        <strong>{{ $l->name }}</strong>
                        <small>{{ $l->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="z-status z-status-{{ $l->status }}">{{ ucfirst($l->status) }}</span>
                </div>
            @empty
                <p class="text-muted text-center py-4 mb-0">No leads assigned</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
