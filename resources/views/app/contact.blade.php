@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.contact')])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Contact Us</h1>
            </div>
        </div>
    </div>
</div>

<div id="contact-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h4><i class="fas fa-phone mr-3"></i>Contact No</h4>
                <p class="mb-5"> 016-303 1808</p>
                <h4><i class="fas fa-map-marker-alt mr-3"></i>Address</h4>
                <p class="mb-5">104, Jalan Impian Indah 3, Taman Impian Indah, Bukit Jalil, 57000 Kuala Lumpur</p>
                <h4><i class="fas fa-envelope mr-3"></i>Email</h4>
                <p>info@rhinosite.com.my</p>
            </div>
        </div>
    </div>
</div>

@endsection