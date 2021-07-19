@extends('layouts.master', ['title' => __('modules.dashboard'), 'guest_view' => true])

@section('content')

<a href="{{ route('verifications.create') }}" role="button" class="btn btn-primary">Join Merchant</a>

@endsection