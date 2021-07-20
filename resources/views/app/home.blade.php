@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.home')])

@section('content')

<div id="home-s1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8 text-center">
                <h1>
                    The go-to for valuble audiences.<br>
                    <span class="home-s1-subtitle">Malaysia's leading business listing directory</span>
                </h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 position-relative">
                <form>
                    <input type="text" name="search" class="home-s1-searchbar" placeholder="Search Your Preferences">
                    <button type="submit" class="home-s1-searchicon"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="home-category">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="category-title">Top Search Categories</p>
            </div>
            <div class="col-md-6 col-lg-3 d-inline-flex">
                <div class="category-item">
                    <img src="{{ asset('storage/assets/home/icon1.png') }}" alt="shopping_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>BUILDING CONSTRUCTION</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 d-inline-flex">
                <div class="category-item">
                    <img src="{{ asset('storage/assets/home/icon2.png') }}" alt="food_drink_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>RENOVATION SERVICES</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 d-inline-flex">
                <div class="category-item">
                    <img src="{{ asset('storage/assets/home/icon3.png') }}" alt="healthy_beauty_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>OFFICE FURNITURE</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 d-inline-flex">
                <div class="category-item">
                    <img src="{{ asset('storage/assets/home/icon4.png') }}" alt="computer_internet_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>SPACE OPTIMIZATION</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-s2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2>Recently Deal Merchant</h2>
            </div>
            <div class="col-12">
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
        <div class="row">
            <div class="col text-center">
                <a href="featured.html" class="btn btn-orange">More Services</a>
            </div>
        </div>
    </div>
</div>

<div id="home-s3">
    <img src="{{ asset('storage/assets/home/s3-left.png') }}" alt="s3_image" class="home-s3-left">
    <div class="home-s3-right">
        <p>JOIN US AS MERCHANT</p>
        <a href="join.html" class="btn btn-orange">I'm Interested</a>
    </div>
</div>
</div>

<div id="home-s4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center">Our Partnership with Merchant</h2>
            </div>
            <div class="col-lg-8">
                <p class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam</p>
            </div>
            <div class="col-lg-10">
                <div class="row justify-content-center">
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/1.jpg') }}" alt="partner_image_1" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/2.jpg') }}" alt="partner_image_2" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/3.jpg') }}" alt="partner_image_3" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/4.png') }}" alt="partner_image_4" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/5.jpg') }}" alt="partner_image_5" class="home-s4-img"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection