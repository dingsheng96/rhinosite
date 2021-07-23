@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.contact')])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.contact_title_main') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="contact-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h4><i class="fas fa-phone mr-3"></i>{{ __('app.contact_contact_no_title') }}</h4>
                <p class="paragraph">{{ __('app.contact_contact_no_value') }}</p>
                <h4><i class="fas fa-map-marker-alt mr-3"></i>{{ __('app.contact_address_title') }}</h4>
                <p class="paragraph">{{ __('app.contact_address_value') }}</p>
                <h4><i class="fas fa-envelope mr-3"></i>{{ __('app.contact_email_title') }}</h4>
                <p class="paragraph">{{ __('app.contact_email_value') }}</p>
            </div>
        </div>
    </div>
</div>

@endsection