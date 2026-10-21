@extends('layouts.admin')
@section('title', 'Add Lead')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.leads') }}">Leads</a>
    <i class="bi bi-chevron-right"></i><span>Add New</span>
@endsection

@section('content')

<div class="z-page-head">
    <h1>Add New Lead</h1>
    <a href="{{ route('admin.leads') }}" class="z-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.leads.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="z-card">
                <h5>Lead Information</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label>Full Name *</label><input type="text" name="name" class="z-input" value="{{ old('name') }}" required></div>
                    <div class="col-md-6"><label>Phone *</label><input type="tel" name="phone" class="z-input" value="{{ old('phone') }}" required></div>
                    <div class="col-md-6"><label>Email</label><input type="email" name="email" class="z-input" value="{{ old('email') }}"></div>
                    <div class="col-md-6">
                        <label>Source *</label>
                        <select name="source" class="z-select w-100" required>
                            <option value="website">Website</option>
                            <option value="phone">Phone</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="walkin">Walk-in</option>
                            <option value="referral">Referral</option>
                            <option value="social">Social</option>
                            <option value="email">Email</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6"><label>Product Interest</label><input type="text" name="product_interest" class="z-input" value="{{ old('product_interest') }}"></div>
                    <div class="col-md-6"><label>Estimated Value</label><input type="number" step="0.01" name="estimated_value" class="z-input" value="{{ old('estimated_value', 0) }}"></div>
                    <div class="col-12"><label>Remarks</label><textarea name="remarks" class="z-input" rows="3">{{ old('remarks') }}</textarea></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="z-card mb-3">
                <h5>Status & Priority</h5>
                <div class="mb-3">
                    <label>Status *</label>
                    <select name="status" class="z-select w-100" required>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="interested">Interested</option>
                        <option value="followup">Follow-up</option>
                        <option value="negotiation">Negotiation</option>
                        <option value="converted">Converted</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Priority *</label>
                    <select name="priority" class="z-select w-100" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label>Assign Agent</label>
                    <select name="assigned_agent_id" class="z-select w-100">
                        <option value="">Unassigned</option>
                        @foreach($agents as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="z-card">
                <button type="submit" class="z-btn-primary w-100 mb-2"><i class="bi bi-check2"></i> Create Lead</button>
                <a href="{{ route('admin.leads') }}" class="z-btn-ghost w-100 justify-content-center">Cancel</a>
            </div>
        </div>
    </div>
</form>

@endsection
