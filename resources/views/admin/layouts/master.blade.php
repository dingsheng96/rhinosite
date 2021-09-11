<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif</title>
    <meta name="description" content="No.1 Contractor Platform in Malaysia Compare Price, Project Portfolios and Contractor's Specialization.">
    <meta property="og:title" content="{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:description" content="No.1 Contractor Platform in Malaysia Compare Price, Project Portfolios and Contractor's Specialization.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('storage/logo_og.png?v=' . time()) }}">


    <link rel="icon" href="{{ asset('storage/rhino_title.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css?v=' . time()) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dropzone-5.7.0/dist/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <style>
        .badge-padding {
            padding: 0.8rem 1.6rem;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @auth('admin')

    <div class="wrapper">

        @include('admin.layouts.topnav')
        @include('admin.layouts.sidenav')

        <div class="content-wrapper">
            @include('admin.layouts.header')
            <div class="content">
                @includeWhen(Session::has('success') || Session::has('fail') ||$errors->any(), 'components.alert')
                @yield('content')
            </div>
        </div>

        @include('admin.layouts.footer') {{-- auth footer --}}

        @include('components.loader')

    </div>

    @endauth

    @guest

    @include('layouts.topnav')

    @yield('content')

    @include('layouts.footer')

    @endguest

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/datepicker.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/slick-img.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/merchant.js?v=' . time()) }}"></script>
    @stack('scripts')

</body>

</html>