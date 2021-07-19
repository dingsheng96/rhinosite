<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css?v=' . time()) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dropzone-5.7.0/dist/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css?v=' . time()) }}">
    @include('components.styles')

</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @if (Auth::check() && (!isset($guest_view) || !$guest_view))

    <div class="wrapper">

        @include('layouts.topnav') {{-- auth topnav --}}
        @include('layouts.sidenav') {{-- auth sidenav --}}

        <div class="content-wrapper">
            @include('layouts.header') {{-- auth content header --}}
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

    @yield('content')

    @endif

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/datepicker.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/style.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    @include('components.scripts')
    @stack('scripts')

</body>

</html>