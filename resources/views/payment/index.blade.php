@extends('layouts.master', ['title' => 'Redirect payment', 'guest_view' => true, 'blank' => true])

@section('content')

@dd($payment_url, $credentials)

<form method="post" name="ePayment" action="{{ $payment_url }}">

    @foreach ($credentials as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

</form>

@endsection

@push('scripts')

<script type="text/javascript">
    window.onload = function() {
        document.forms['ePayment'].submit();
    }
</script>

@endpush