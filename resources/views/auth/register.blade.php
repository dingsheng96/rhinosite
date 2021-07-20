@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.register')])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Register</h1>
            </div>
        </div>
    </div>
</div>

<div id="register-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 d-inline-flex">
                <div class="login-container">
                    <p class="login-title">
                        Registration Form
                    </p>
                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" role="form">

                        @csrf

                        <div class="input-group">
                            <p class="login-text">Name <span class="text-danger">*</span></p>
                            <input type="text" name="name" id="name" class="@error('name') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Phone <span class="text-danger">*</span></p>
                            <input type="text" name="phone" id="phone" class="@error('phone') is-invalid @enderror">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Email Address <span class="text-danger">*</span></p>
                            <input type="email" name="email" id="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Password <span class="text-danger">*</span></p>
                            <input type="password" name="password" id="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Confirm Password <span class="text-danger">*</span></p>
                            <input type="text" name="password_confirmation" id="password_confirmation">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Address 1 <span class="text-danger">*</span></p>
                            <input type="text" name="address_1" id="address_1">
                            @error('address_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Address 2 <span class="text-danger">*</span></p>
                            <input type="text" name="address_2" id="address_2">
                            @error('address_2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Postcode <span class="text-danger">*</span></p>
                            <input type="text" name="postcode" id="postcode">
                            @error('postcode')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">Country <span class="text-danger">*</span></p>
                            <select name="country" id="country">
                                <option value="">--Select country--</option>
                            </select>
                            @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">State <span class="text-danger">*</span></p>
                            <select name="state" id="state">
                                <option value="">--Select state--</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                            </select>
                            @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <p class="login-text">City <span class="text-danger">*</span></p>
                            <select name="city" id="city">
                                <option value="">--Select city--</option>
                            </select>
                            @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="agree" name="agree" value="agree" class="mt-2">
                            <div class="col mb-5 mt-1">
                                <label class="login-text" for="agree" class="mb-0">I agree to the <a target="blank" href="{{ route('app.term') }}">terms and condition</a> & <a target="blank" href="{{ route('app.privacy') }}">privacy policy</a> of Rhinosite.</label>
                            </div>
                        </div>

                        <button type="submit" href="{{ route('register') }}" class="btn btn-black w-100 ml-0 mb-3">Register</button>

                    </form>
                </div>
            </div>
            <div class="col-lg-6 d-inline-flex px-0">
                <img src="{{ asset('storage/register.jpg') }}" alt="register_image" class="res-img">
            </div>
        </div>
    </div>
</div>

@endsection