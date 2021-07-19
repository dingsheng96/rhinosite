@extends('layouts.master', ['title' => '', 'guest_view' => true])

@section('content')

<form method="post" name="ePayment" action="{{ $redirect_url }}">

    <input type="hidden" name="MerchantCode" value="{{ $credentials['merchant_code'] }}">
    <input type="hidden" name="PaymentId" value="{{ $credentials['payment_id'] }}">
    <input type="hidden" name="RefNo" value="{{ $credentials['ref_no'] }}">
    <input type="hidden" name="Amount" value="{{ $credentials['amount'] }}">
    <input type="hidden" name="Currency" value="{{ $credentials['currency'] }}">
    <input type="hidden" name="ProdDesc" value="{{ $credentials['prod_desc'] }}">
    <input type="hidden" name="UserName" value="{{ $credentials['user_name'] }}">
    <input type="hidden" name="UserEmail" value="{{ $credentials['user_email'] }}">
    <input type="hidden" name="UserContact" value="{{ $credentials['user_contact'] }}">
    <input type="hidden" name="Remark" value="{{ $credentials['remark'] }}">
    <input type="hidden" name="Lang" value="{{ $credentials['lang'] }}">
    <input type="hidden" name="SignatureType" value="{{ $credentials['signature_type'] }}">
    <input type="hidden" name="Signature" value="{{ $credentials['signature'] }}">
    <input type="hidden" name="ResponseURL" value="{{ $credentials['response_url'] }}">
    <input type="hidden" name="BackendURL" value="{{ $credentials['backend_url'] }}">

</form>

@endsection

@push('scripts')

<script type="text/javascript">
    window.onload = function(){
        document.forms['ePayment'].submit();
    }
</script>

@endpush