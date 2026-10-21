@extends('layouts.admin')
@section('title', 'Follow-ups')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <span>CRM</span>
    <i class="bi bi-chevron-right"></i><span>Follow-ups</span>
@endsection

@section('content')

<div class="z-page-head">
    <div>
        <h1>Follow-ups</h1>
        <p>Track all scheduled follow-ups</p>
    </div>
</div>

<div class="z-card">
    <div class="table-responsive">
        <table class="table z-table">
            <thead>
                <tr><th>Lead</th><th>Type</th><th>Scheduled</th><th>Agent</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($followups as $f)
                <tr>
                    <td>
                        <strong>{{ $f->lead->name ?? '—' }}</strong>
                        <small class="d-block text-muted">{{ $f->lead->lead_code ?? '' }}</small>
                    </td>
                    <td>{{ ucfirst($f->type) }}</td>
                    <td>{{ $f->scheduled_at->format('d M Y, h:i A') }}</td>
                    <td>{{ $f->agent->name ?? '—' }}</td>
                    <td><span class="z-status z-status-{{ $f->status === 'completed' ? 'delivered' : 'pending' }}">{{ ucfirst($f->status) }}</span></td>
                    <td>
                        @if($f->status === 'pending')
                            <button class="z-act-btn complete-fu" data-id="{{ $f->id }}" title="Mark Complete">
                                <i class="bi bi-check2"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No follow-ups scheduled</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $followups->links() }}
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('.complete-fu').on('click', function () {
        const id = $(this).data('id');
        $.post('{{ url("/admin/crm/followups") }}/' + id + '/complete')
            .done(() => { zToast('Marked complete', 'success'); setTimeout(() => location.reload(), 600); })
            .fail(() => zToast('Failed', 'error'));
    });
});
</script>
@endpush
