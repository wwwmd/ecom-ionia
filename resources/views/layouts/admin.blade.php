<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — Store Admin</title>

<!-- Bootstrap + Icons + DataTables -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

<!-- App CSS -->
<link rel="stylesheet" href="{{ asset('public/assets/css/store.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/css/admin.css') }}">


@stack('styles')
</head>
<body class="z-admin-body">

@php
    $isAgent = auth()->check() && auth()->user()->isAgent();
@endphp

<div class="z-admin-wrap">

    {{-- Sidebar --}}
    @if($isAgent)
        @include('partials.agent-sidebar')
    @else
        @include('partials.admin-sidebar')
    @endif

    {{-- Main area --}}
    <div class="z-admin-main">

        {{-- Topbar --}}
        @include('partials.admin-topbar')

        {{-- Content --}}
        <div class="z-admin-content">

            {{-- Breadcrumb --}}
            <nav class="z-admin-breadcrumb">
                <a href="{{ $isAgent ? url('/agent/dashboard') : url('/admin/dashboard') }}">
                    <i class="bi bi-house-door"></i> Home
                </a>
                @yield('breadcrumb')
            </nav>

            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="z-admin-footer">
            © {{ date('Y') }} Store — Admin Panel
        </footer>
    </div>
</div>

{{-- Premium Center Popup Container --}}
<div class="z-popup-overlay" id="zPopupContainer"></div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.Z_CONFIG = {
        baseUrl: "{{ url('/') }}",
        csrf: "{{ csrf_token() }}",
        isAgent: {{ $isAgent ? 'true' : 'false' }}
    };
</script>

<script src="{{ asset('public/assets/js/store.js') }}"></script>
<script src="{{ asset('public/assets/js/admin.js') }}"></script>

{{-- Auto-show Laravel flash messages as premium popups --}}
<script>
$(function () {
    const successMsg = @json(session('success'));
    const errorMsg   = @json(session('error'));
    const warningMsg = @json(session('warning'));
    const infoMsg    = @json(session('info'));

    if (successMsg && window.zSuccess) window.zSuccess(successMsg);
    else if (errorMsg && window.zError) window.zError(errorMsg);
    else if (warningMsg && window.zWarning) window.zWarning(warningMsg);
    else if (infoMsg && window.zInfo) window.zInfo(infoMsg);
});
</script>

@stack('scripts')
</body>
</html>
