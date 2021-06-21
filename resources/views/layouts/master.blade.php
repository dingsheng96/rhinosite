<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') . ' | ' . ($title ?? '') }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('css/style.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('dropzone-5.7.0/dist/dropzone.css?v=' . time()) }}">

</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @auth

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

        @include('layouts.footer')
    </div>

    @else

    @yield('content')

    @endauth

    <script src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/style.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/modal.js?v=' . time()) }}"></script>
    @stack('scripts')

</body>

</html>