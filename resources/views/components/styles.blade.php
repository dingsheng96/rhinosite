@if (Auth::check() && (!isset($guest_view) || !$guest_view))

<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css?v=' . time()) }}">

@endif