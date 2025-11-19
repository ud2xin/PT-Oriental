<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    {{-- Vite entries --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Font Awesome SB Admin uses (from public vendor) --}}
    <link href="{{ asset('sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('vendor/sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            @include('layouts.topbar')
            <div id="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    {{-- SB Admin and dependencies (from public) --}}
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/js/sb-admin-2.min.js') }}"></script>
    @stack('scripts')
</body>

</html>