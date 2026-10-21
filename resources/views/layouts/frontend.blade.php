<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'store Store — Premium Shopping')</title>
<meta name="description" content="@yield('meta_description', 'Shop premium electronics, fashion & more at store Technologies.')">

<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
>
<link rel="stylesheet" href="{{ asset('public/assets/css/store.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/css/front.css') }}">
@stack('styles')
</head>
<body>
 @include('partials.header')

<main class="z-main">
    @yield('content')
</main>
 @include('partials.footer')
@include('partials.toast')



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.Z_CONFIG = {
        baseUrl: "{{ url('/') }}",
        csrf: "{{ csrf_token() }}",
        isAuth: {{ auth()->check() ? 'true' : 'false' }}
    };
</script>
<script src="{{ asset('public/assets/js/store.js') }}"></script>
<script src="{{ asset('public/assets/js/front.js') }}"></script>
@stack('scripts')
</body>
</html>
