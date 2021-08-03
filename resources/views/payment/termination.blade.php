@extends('layouts.master', ['title' => '', 'guest_view' => true, 'blank' => true])

@section('content')

<form method="post" name="terminateForm" action="{{ $termination_url }}">

    @foreach ($credentials as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

</form>

@endsection

@push('scripts')

<script type="text/javascript">
    window.onload = function() {
        document.forms['terminateForm'].submit();
    }
</script>

@endpush