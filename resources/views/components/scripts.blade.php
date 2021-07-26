@if (Auth::check() && (!isset($guest_view) || !$guest_view))

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="{{ asset('js/style.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/modal.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/dropzone.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/dynamic-form.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/cart.js?v=' . time()) }}"></script>

@else

<script type="text/javascript" src="{{ asset('js/slick-img.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/merchant.js?v=' . time()) }}"></script>

@endif

<script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/datepicker.js?v=' . time()) }}"></script>