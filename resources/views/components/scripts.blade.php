<script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript" src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
<script type="text/javascript" src="{{ asset('js/datepicker.js?v=' . time()) }}"></script>

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