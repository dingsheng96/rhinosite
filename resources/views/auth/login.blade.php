@extends('layouts.master', ['title' => __('modules.login'), 'body' => 'enduser', 'guest_view' => true])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Login</h1>
            </div>
        </div>
    </div>
</div>

<div id="login-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 d-inline-flex">
                <div class="login-container">
                    <!-- Nav tabs -->
                    <ul class="nav">
                        <li class="user">
                            <a class="user active" data-toggle="tab" href="#userlogin">Existing User</a>
                        </li>
                        <li class="user">
                            <a class="user" data-toggle="tab" href="#merchant">Merchant</a>
                        </li>
                    </ul>
                    <p class="login-title">
                        Welcome to Rhinosite
                    </p>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="userlogin">

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

                                <p class="login-text text-right mb-4"><u><a href="#" class="txtgrey">Forgot Password</a></u></p>

                                <button type="submit" class="btn btn-black w-100 ml-0 mb-3">{{ __('labels.sign_in') }}</button>
                            </form>

                            <a href="#" class="btn btn-blue w-100">Continue with Facebook</a>
                            <div class="text-center my-3">
                                <span class="login-text">New Member? </span><a href="{{ route('register') }}" class="login-text text-underline txtorange"><u>Register Now</u></a>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="merchant">

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

                                <p class="login-text text-right mb-4"><u><a href="#" class="txtgrey">Forgot Password</a></u></p>

                                <button type="submit" class="btn btn-black w-100 ml-0 mb-3">{{ __('labels.sign_in') }}</button>
                            </form>

                            <div class="text-center">
                                <span class="login-text">Wish to be a Merchant? </span>
                                <a href="{{ route('register') }}" class="login-text text-underline txtorange"><u>Join Now</u></a>
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