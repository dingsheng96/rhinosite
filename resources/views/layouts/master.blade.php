<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif</title>

    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('css/style.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('dropzone-5.7.0/dist/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />


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

    <script src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/style.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/modal.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/dropzone.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/dynamic-form.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/cart.js?v=' . time()) }}"></script>
    @stack('scripts')

</body>

</html>