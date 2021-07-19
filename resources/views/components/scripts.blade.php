@if (Auth::check() && (!isset($guest_view) || !$guest_view))

<script type="text/javascript" src="{{ asset('js/modal.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/dropzone.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/dynamic-form.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/cart.js?v=' . time()) }}"></script>

@endif