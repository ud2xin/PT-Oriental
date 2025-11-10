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

    <script src="{{ asset('vendor/sb-admin-2/js/sb-admin-2.min.js') }}"></script>

    <body id="page-top">
        <div id="wrapper">
            @include('layouts.sidebar')
            <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.topbar')
                <div class="container-fluid">
                @yield('content')
                </div>
            </div>
            @include('layouts.footer')
            </div>
        </div>

        {{-- SB Admin and dependencies (from public) --}}
        <script src="{{ asset('sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('sb-admin-2/js/sb-admin-2.min.js') }}"></script>
        @stack('scripts')
    </body>
</html>
