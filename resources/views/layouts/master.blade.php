<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('components.styles')

</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @if (Auth::check() && (!isset($guest_view) || !$guest_view))

    <div class="wrapper">

        @include('layouts.topnav')
        @include('layouts.sidenav')

        <div class="content-wrapper">
            @include('layouts.header')
            <div class="content">
                @includeWhen(Session::has('success') || Session::has('fail') ||$errors->any(), 'components.alert')
                @yield('content')
            </div>
        </div>

        @include('layouts.footer') {{-- auth footer --}}

        @include('components.loader')

        @includeWhen(Auth::user()->is_merchant, 'cart.index')
    </div>

    @else

    @include('layouts.topnav')

    @yield('content')

    @include('layouts.footer')

    @endif

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
    @include('components.scripts')
    @stack('scripts')

</body>

</html>