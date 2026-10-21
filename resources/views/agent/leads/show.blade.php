@extends('layouts.admin')
@section('title', 'Lead Details')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('agent.leads') }}">My Leads</a>
    <i class="bi bi-chevron-right"></i><span>{{ $lead->lead_code }}</span>
@endsection

@section('content')

<div class="z-page-head">
    <h1>{{ $lead->name }}</h1>
    <a href="{{ route('agent.leads') }}" class="z-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="z-card mb-3">
            <h5>Lead Details</h5>
            <div class="row g-3">
                <div class="col-md-6"><strong>Phone:</strong> {{ $lead->phone }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $lead->email ?? '—' }}</div>
                <div class="col-md-6"><strong>Source:</strong> {{ ucfirst($lead->source) }}</div>
                <div class="col-md-6"><strong>Interest:</strong> {{ $lead->product_interest ?? '—' }}</div>
            </div>
        </div>

        <div class="z-card">
            <h5>Follow-ups</h5>
            @forelse($lead->followups as $f)
                <div style="padding:12px 0;border-bottom:1px solid var(--z-border)">
                    <div class="d-flex justify-content-between">
                        <strong>{{ ucfirst($f->type) }}</strong>
                        <span class="z-status z-status-{{ $f->status === 'completed' ? 'delivered' : 'pending' }}">{{ ucfirst($f->status) }}</span>
                    </div>
                    <small class="text-muted">{{ $f->scheduled_at->format('d M Y, h:i A') }}</small>
                    @if($f->notes)<p class="mb-0 mt-2">{{ $f->notes }}</p>@endif
                </div>
            @empty
                <p class="text-muted mb-0">No follow-ups yet.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="z-card">
            <h5>Status</h5>
            <span class="z-status z-status-{{ $lead->status }}">{{ ucfirst($lead->status) }}</span>
            <div class="mt-3"><span class="z-priority z-priority-{{ $lead->priority }}">{{ $lead->priority }}</span></div>
        </div>
    </div>
</div>

@endsection
