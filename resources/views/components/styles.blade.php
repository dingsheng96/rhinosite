@if (Auth::check() && (!isset($guest_view) || !$guest_view))

<link rel="stylesheet" type="text/css" href="{{ asset('dropzone-5.7.0/dist/dropzone.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/portal.css?v=' . time()) }}">

@else

<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css?v=' . time()) }}">

@endif