@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.merchant_profile')])

@section('content')

<div id="profile-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Merchant Profile</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <form>
                        <input type="text" name="search" class="searchbar" placeholder="Search Your Preferences">
                        <button type="submit" class="searchicon"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="merchant-category">
    <div class="container">
        <div class="d-flex px-3">
            <span>Top Search Categories</span>
            <ul>
                <li class="active">Awning</li>
                <li class="active">Partition</li>
                <li>Wall Drilling & Mounting</li>
                <li>Flooring Installation</li>
                <li>Glasswork</li>
            </ul>
        </div>
    </div>
</div>

<div id="profile-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Services</a></li>
                    <li class="breadcrumb-item"><a href="#">Contracter</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Magna Klasik</li>
                </ol>
            </div>
        </div>
        <div class="profile-card">
            <div class="col-lg-7 col-xl-8 align-self-center">
                <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="merchant_profile_pic" class="profile-img">
                <span class="profile-name">Magna Klasik</span>
            </div>
            <div class="col-lg-5 col-xl-4 text-right align-self-center">
                <p>Joined Since : Jan 2021</p>
                <p>Year in Industry : 10 years</p>
                <p>
                    <i class="fa fa-star star"></i>
                    <i class="fa fa-star star"></i>
                    <i class="fa fa-star star"></i>
                    <i class="fa fa-star star"></i>
                    <i class="fa fa-star-o star"></i>
                </p>
                <button type="button" class="btn btn-orange" data-toggle="modal" data-target="#ratemerchant">
                    Rate Merchant
                </button>
            </div>
            <div class="profile-line"></div>
            <div class="col-lg-8">
                <div class="d-flex mb-3 align-items-center"><i class="fa fa-phone profile-icon" aria-hidden="true"></i><span class="ml-3">+6016 8329 8302</span></div>
                <div class="d-flex mb-3 align-items-center"><i class="fa fa-map-marker profile-icon location" aria-hidden="true"></i><span class="ml-3">No 10-12, Jalan Putra Shed, Kota Kemuning, 39203, Kuala Lumpur, Malaysia</span></div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex mb-3 align-items-center text-break"><i class="fa fa-envelope profile-icon mail" aria-hidden="true"></i><span class="ml-3">contractor@gmail.com</span></div>
            </div>
        </div>
    </div>
</div>

<div id="profile-2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2>Provided services:</h2>
            </div>
            <div class="col-12 mb-3">
                <p class="txtgrey">Services:</p>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">Awning</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">Partition</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">TV Mounting</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">Flooring Installation</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">Glasswork</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="profile-3">
    <div class="container">
        <h4>Magna Klasik Projects</h4>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="merchantservice.html">
                        <div class="merchant-image-container">
                            <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="merchant_1" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">Magna Klasik Sdn. Bhd.</p>
                            <p class="merchant-subtitle">Magna Klasik</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From RM1,200</span>
                            <span class="merchant-footer-right">Kuala Lumpur</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="merchantservice.html">
                        <div class="merchant-image-container">
                            <img src="{{ asset('storage/assets/home/2.jpg') }}" alt="merchant_2" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">Zie Global Trading (M) Sdn Bhd</p>
                            <p class="merchant-subtitle">Zie Global</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From RM10,000</span>
                            <span class="merchant-footer-right">Kuala Lumpur</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="merchantservice.html">
                        <div class="merchant-image-container">
                            <img src="{{ asset('storage/assets/home/3.jpg') }}" alt="merchant_3" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">IBZ DAGANG</p>
                            <p class="merchant-subtitle">IBZ DAGANG</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From RM1,500</span>
                            <span class="merchant-footer-right">Kuala Lumpur</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="merchantservice.html">
                        <div class="merchant-image-container">
                            <img src="{{ asset('storage/assets/home/4.png') }}" alt="merchant_4" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">Sansel Business Solutions Sdn. Bhd.</p>
                            <p class="merchant-subtitle">Sansel Business</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From RM2,800</span>
                            <span class="merchant-footer-right">Selangor</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="merchantservice.html">
                        <div class="merchant-image-container">
                            <img src="{{ asset('storage/assets/home/5.jpg') }}" alt="merchant_5" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">ARTSYSTEM (M) SDN. BHD.</p>
                            <p class="merchant-subtitle">ARTSYSTEM</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From RM1,500</span>
                            <span class="merchant-footer-right">Selangor</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="merchantservice.html">
                        <div class="merchant-image-container">
                            <img src="{{ asset('storage/assets/home/6.png') }}" alt="merchant_6" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">Malton Berhad</p>
                            <p class="merchant-subtitle">Malton</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From RM2,300</span>
                            <span class="merchant-footer-right">Kuala Lumpur</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection