@extends('layouts.admin')
@section('title', 'Lead Details')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.leads') }}">Leads</a>
    <i class="bi bi-chevron-right"></i><span>{{ $lead->lead_code }}</span>
@endsection

@section('content')

<div class="z-page-head">
    <div>
        <h1>{{ $lead->name }}</h1>
        <p>{{ $lead->lead_code }}</p>
    </div>
    <div class="z-page-head-actions">
        <button class="z-btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal"><i class="bi bi-person-plus"></i> Assign Agent</button>
        <button class="z-btn-ghost" data-bs-toggle="modal" data-bs-target="#followupModal"><i class="bi bi-calendar-plus"></i> Schedule Follow-up</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="z-card mb-3">
            <h5>Lead Information</h5>
            <div class="row g-3">
                <div class="col-md-6"><strong>Phone:</strong> {{ $lead->phone }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $lead->email ?? '—' }}</div>
                <div class="col-md-6"><strong>Source:</strong> {{ ucfirst($lead->source) }}</div>
                <div class="col-md-6"><strong>Product Interest:</strong> {{ $lead->product_interest ?? '—' }}</div>
                <div class="col-md-6"><strong>Estimated Value:</strong> ₹{{ number_format($lead->estimated_value) }}</div>
                <div class="col-md-6"><strong>Agent:</strong> {{ $lead->agent->name ?? 'Unassigned' }}</div>
            </div>
            @if($lead->remarks)
                <hr>
                <strong>Remarks:</strong>
                <p class="mb-0 text-muted">{{ $lead->remarks }}</p>
            @endif
        </div>

        <div class="z-card">
            <h5>Follow-up History</h5>
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
                <p class="text-muted">No follow-ups yet.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="z-card">
            <h5>Status</h5>
            <span class="z-status z-status-{{ $lead->status }}" style="font-size:14px;padding:8px 16px">{{ ucfirst($lead->status) }}</span>
            <div style="margin-top:12px"><span class="z-priority z-priority-{{ $lead->priority }}">{{ $lead->priority }}</span></div>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content z-modal">
            <div class="modal-header"><h5>Assign Lead</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <label>Select Agent</label>
                <select class="z-select w-100" id="agentSelect">
                    <option value="">-- Choose --</option>
                    @foreach($agents as $a)
                        <option value="{{ $a->id }}" @selected($lead->assigned_agent_id == $a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button class="z-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button class="z-btn-primary" id="confirmAssign">Assign</button>
            </div>
        </div>
    </div>
</div>

<!-- Followup Modal -->
<div class="modal fade" id="followupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content z-modal">
            <div class="modal-header"><h5>Schedule Follow-up</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <label>Type</label>
                <select class="z-select w-100 mb-3" id="fuType">
                    <option value="call">Phone Call</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="email">Email</option>
                    <option value="meeting">Meeting</option>
                    <option value="other">Other</option>
                </select>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label>Date</label><input type="date" class="z-input" id="fuDate" value="{{ date('Y-m-d') }}"></div>
                    <div class="col-6"><label>Time</label><input type="time" class="z-input" id="fuTime" value="10:00"></div>
                </div>
                <label>Notes</label>
                <textarea class="z-input" id="fuNotes" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button class="z-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button class="z-btn-primary" id="confirmFollowup">Schedule</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('#confirmAssign').on('click', function () {
        $.post('{{ route("admin.leads.assign", $lead) }}', { agent_id: $('#agentSelect').val() })
            .done(() => { zToast('Assigned', 'success'); setTimeout(() => location.reload(), 600); })
            .fail(() => zToast('Failed', 'error'));
    });

    $('#confirmFollowup').on('click', function () {
        $.post('{{ url("/admin/crm/followups") }}', {
            lead_id: {{ $lead->id }},
            type: $('#fuType').val(),
            scheduled_at: $('#fuDate').val() + ' ' + $('#fuTime').val() + ':00',
            notes: $('#fuNotes').val()
        }).done(() => { zToast('Follow-up scheduled', 'success'); setTimeout(() => location.reload(), 600); })
          .fail(() => zToast('Failed', 'error'));
    });
});
</script>
@endpush
