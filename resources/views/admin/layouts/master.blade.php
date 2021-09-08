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


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('storage/rhino_title.png') }}" type="image/x-icon">
    @include('components.styles')

</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @if (Auth::guard('admin')->check() && (!isset($guest_view) || !$guest_view))

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

    @else

    @includeWhen(empty($blank), 'admin.layouts.topnav')

    @yield('content')

    @includeWhen(empty($blank), 'admin.layouts.footer')

    @endif

    @include('components.scripts')
    @stack('scripts')

</body>

</html>