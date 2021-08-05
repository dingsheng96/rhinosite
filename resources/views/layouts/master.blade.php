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
    <link rel="icon" href="{{ asset('storage/rhino_title.png') }}" type="image/x-icon">
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

    @includeWhen(empty($blank), 'layouts.topnav')

    @yield('content')

    @includeWhen(empty($blank), 'layouts.footer')

    @endif

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
    @include('components.scripts')
    @stack('scripts')

</body>

</html>