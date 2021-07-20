@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.service')])

@section('content')

<div id="services-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Services</h1>
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

<div id="services-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Services</a></li>
                    <li class="breadcrumb-item"><a href="#">Contractor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Awning</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xxl-7 d-inline-flex flex-column align-self-center mb-lg-0 mb-3">
                <div class="slider slider-for">
                    <img src="{{ asset('storage/assets/service/1.jpg') }}" alt="service_main_image" class="services-main-img">
                    <img src="{{ asset('storage/assets/service/2.jpg') }}" alt="service_sub_image1" class="services-main-img">
                    <img src="{{ asset('storage/assets/service/3.jpg') }}" alt="service_sub_image2" class="services-main-img">
                    <img src="{{ asset('storage/assets/service/4.jpg') }}" alt="service_sub_image3" class="services-main-img">
                    <img src="{{ asset('storage/assets/service/5.jpg') }}" alt="service_sub_image4" class="services-main-img">
                    <img src="{{ asset('storage/assets/service/6.jpg') }}" alt="service_sub_image5" class="services-main-img">
                </div>
                <div class="slider slider slider-nav">
                    <img src="{{ asset('storage/assets/service/1.jpg') }}" alt="service_main_image" class="services-small-img">
                    <img src="{{ asset('storage/assets/service/2.jpg') }}" alt="service_sub_image1" class="services-small-img">
                    <img src="{{ asset('storage/assets/service/3.jpg') }}" alt="service_sub_image2" class="services-small-img">
                    <img src="{{ asset('storage/assets/service/4.jpg') }}" alt="service_sub_image3" class="services-small-img">
                    <img src="{{ asset('storage/assets/service/5.jpg') }}" alt="service_sub_image4" class="services-small-img">
                    <img src="{{ asset('storage/assets/service/6.jpg') }}" alt="service_sub_image5" class="services-small-img">
                </div>
            </div>
            <div class="col-lg-6 col-xxl-5 d-inline-flex">
                <div class="container bg-white services-details">
                    <div class="row align-items-center py-4">
                        <div class="col-5 col-md-6">
                            <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="service_brand" class="services-img">
                        </div>
                        <div class="col-7 col-md-6 btn-right">
                            <a href="{{ route('app.merchant.show') }}" class="btn btn-black">View Merchant</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <p class="services-title">Magna Klasik Awning</p>

                        </div>
                        <div class="col-sm-5 text-sm-right">
                            <button class="btn"><i class="fas fa-paste txtorange services-icon" aria-hidden="true"></i></button>
                            <button class="btn"><i class="fab fa-whatsapp txtgreen services-icon" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-12">
                            <p class="services-subtitle">All types of Awning</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>Price</p>
                        </div>
                        <div class="col-sm-6 text-sm-right txtblk">
                            <p>
                                <span class="services-from">From</span>
                                <span class="services-price pl-2">RM888</span>
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>Location</p>
                        </div>
                        <div class="col-sm-6 txtblk text-sm-right">
                            <p class="font-medium">Kuala Lumpur</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>Contact Contractor</p>
                        </div>
                        <div class="col-sm-6 txtblk text-sm-right">
                            <p>+6016 382 7293</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="services-2">
    <div class="container">
        <h3>Services Provided</h3>
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="services-card">
                    <p class="services-card-title">Magna Klasik</p>
                    <p class="font-medium">Awning</p>
                    <p class="services-card-cn">Awning</p>
                    <p>Lorem ipsum dolor sit ametd do eiusmod</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="services-card">
                    <p class="services-card-title">Magna Klasik</p>
                    <p class="font-medium">Glasswork</p>
                    <p class="services-card-cn">Glasswork</p>
                    <p>Lorem ipsum dolor sit ametd do eiusmod</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="services-card">
                    <p class="services-card-title">Magna Klasik</p>
                    <p class="font-medium">Concreting</p>
                    <p class="services-card-cn">Concreting</p>
                    <p>Lorem ipsum dolor sit ametd do eiusmod</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="services-card">
                    <p class="services-card-title">Magna Klasik</p>
                    <p class="font-medium">Window Tinting</p>
                    <p class="services-card-cn">Window tinting</p>
                    <p>Lorem ipsum dolor sit ametd do eiusmod</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="services-card">
                    <p class="services-card-title">Magna Klasik</p>
                    <p class="font-medium">Plastering</p>
                    <p class="services-card-cn">Plastering</p>
                    <p>Lorem ipsum dolor sit ametd do eiusmod</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="services-card">
                    <p class="services-card-title">Magna Klasik</p>
                    <p class="font-medium">Ironwork</p>
                    <p class="services-card-cn">Ironwork</p>
                    <p>Lorem ipsum dolor sit ametd do eiusmod</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="services-5">
    <div class="container">
        <h3>Similar Projects</h3>
        <div class="row">
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