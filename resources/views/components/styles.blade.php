<link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('dropzone-5.7.0/dist/dropzone.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css?ver=5.8" media="all" />

@if (Auth::check() && (!isset($guest_view) || !$guest_view))

<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css?v=' . time()) }}">

@else

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap">
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css?v=' . time()) }}">

@endif

<style>
    .full-height {
        height: 100vh;
    }

    .three-quarter-height {
        height: 75vh;
    }

    .code {
        border-right: 2px solid;
        font-size: 26px;
        padding: 0 15px 0 15px;
        text-align: center;
    }

    .message {
        font-size: 18px;
        text-align: center;
    }

    .badge-padding {
        padding: 0.8rem 1.6rem;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
</style>