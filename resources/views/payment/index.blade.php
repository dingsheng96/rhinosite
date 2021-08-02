@extends('layouts.master', ['title' => '', 'guest_view' => true, 'blank' => true])

@section('content')

{{-- <form method="post" name="ePayment" action="{{ $payment_url }}">

@foreach ($credentials as $key => $value)
<input type="hidden" name="{{ $key }}" value="{{ $value }}">
@endforeach

</form> --}}
@dd($credentials, $payment_url)

@endsection

@push('scripts')

{{-- <script type="text/javascript">
    window.onload = function() {
        document.forms['ePayment'].submit();
    }
</script> --}}

@endpush