@extends('layouts.master', ['title' => __('errors.title'), 'guest_view' =>true, 'body' => 'enduser'])

@section('content')
<div class="d-flex justify-content-center align-items-center position-relative three-quarter-height">
    <div class="code">
        @yield('code')
    </div>

    <div class="message" style="padding: 10px;">
        @yield('message')
    </div>
</div>
@endsection