@extends('layouts.master', ['title' => __('modules.login'), 'body' => 'enduser', 'guest_view' => true])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.login_title_main') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="login-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 d-inline-flex">
                <div class="login-container">

                    <ul class="nav">
                        <li class="user">
                            <a class="user active" data-toggle="tab" href="#member">{{ __('app.login_option_member') }}</a>
                        </li>
                        <li class="user">
                            <a class="user" data-toggle="tab" href="#merchant">{{ __('app.login_option_merchant') }}</a>
                        </li>
                    </ul>

                    <p class="login-title">{{ __('app.login_form_title') }}</p>

                    @if (Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        <small>{!! Session::get('success') !!}</small>
                    </div>
                    @endif

                    @if (Session::has('info'))
                    <div class="alert alert-info" role="alert">
                        <small>{!! Session::get('info') !!}</small>
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="post" role="form" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group">
                            <p class="login-text">{{ __('labels.email') }}</p>
                            <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">{{ __('labels.password') }}</p>
                            <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <p class="login-text text-right mb-4"><u><a href="#" class="txtgrey">{{ __('app.login_btn_forgot_password') }}</a></u></p>

                        <button type="submit" class="btn btn-orange w-100 ml-0 mb-3">{{ __('labels.sign_in') }}</button>
                    </form>

                    <div class="tab-content">
                        <div class="tab-pane active" id="member">
                            <a href="#" class="btn btn-blue w-100">{{ __('app.login_btn_facebook') }}</a>
                            <div class="text-center my-3">
                                {!! __('app.login_btn_register_member') !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="merchant">
                            <div class="text-center">
                                {!! __('app.login_btn_register_merchant') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-lg-inline-flex px-0 d-none">
                <img src="{{ asset('storage/assets/home/s3-left.png') }}" alt="login_image" class="res-img">
            </div>
        </div>
    </div>
</div>


@endsection