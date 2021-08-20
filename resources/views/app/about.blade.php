@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.about')])

@section('content')

<div id="about-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.about_title_main') }}</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    @include('app.search')
                </div>
            </div>
        </div>
    </div>
</div>

<div id="about-1">
    <div class="container">
        <h2 class="text-center">About us – Rhinosite’s Journey </h2>
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <p class="paragraph">
                    Prior to 2021, property owners who like to build or renovate their premises will have to engage with a contractor either by their own contact or word of mouth, whilst Contractors are depending on clients’ referral in connecting with new clients. Reason being there
                    are limited online exposure for the Contractors and Users are spending hours vetting through the available sources for a trustable and reliable Contractor within their budget.
                </p>
                <p class="paragraph">
                    Hence, our Founders created Rhinosite to solve these common pain-points faced by property owners and Contractors. Our core value is to create more business opportunities for the construction industry where a Contractor is able to build their long-term
                    relationship with their clients and Users are able to find a contractor in a hassle-free and transparent process.
                </p>
            </div>
        </div>
    </div>
</div>

<div id="about-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-xxl-5 ml-auto">
                <h2>What is Rhinosite? </h2>
                <p class="paragraph">
                    Rhinosite is a digital contractors site, which consolidates all contractors in one platform – ensure finding a contractor is a hassle-free and transparent process for everyone involved. Rhinosite also provides professional project management services to help
                    improve customer-centric relationships.
                </p>
            </div>
            <div class="col-8 col-md-5 col-xxl-5 mr-md-auto mx-auto">
                <img src="{{ asset('storage/logo-footer.png') }}" alt="about_3_image" class="res-img">
            </div>
        </div>
    </div>
</div>


<div id="about-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <h2 class="text-center d-lg-none d-block">Why Rhinosite?</h2>
                <img src="{{ asset('storage/assets/about/whyrhinosite.jpg') }}" class="res-img mb-3 mb-lg-0">
            </div>
            <div class="col-lg-7 pl-lg-5">
                <h2 class="mb-0 d-lg-block d-none">Why Rhinosite?</h2>
                <!-- Tab panes -->
                <div class="tab-content about-tabbed text-lg-left text-center">
                  <div id="menu1" class="tab-pane active"><br>
                    <h3 class="font-weight-bold txtblk mb-2">1. Increase your exposure and presence online</h3>
                    <p class="paragraph mb-0"> - Rhinosite allows you to grow your business by increasing the chances of your business exposure online – to connect property owners to contractors in a single platform.</p>
                  </div>
                  <div id="menu2" class="tab-pane fade"><br>
                    <h3 class="font-weight-bold txtblk mb-2">2. Transparent & Hassle-Free</h3>
                    <p class="paragraph mb-2">- By listing with us, contractors are able to provide their nature of business, past portfolios, location, contact numbers, asking price etc. so that the end users are able to filter according to their needs in a more time-efficient manner.</p>
                    <p class="paragraph mb-2">- No other hidden costs and commission charged. </p>
                  </div>
                  <div id="menu3" class="tab-pane fade"><br>
                    <h3 class="font-weight-bold txtblk mb-2">3. Trustable & Reliable Contractors</h3>
                    <p class="paragraph mb-2">- Rhinosite team will have a due diligence process to ensure that only reliable and quality contractors are able to be listed on our platform.</p>
                    <p class="paragraph mb-2">- Rhinosite will investigate thoroughly on those Contractors whom received poor ratings/ feedback. The Contractor will be terminated if the issue remains unsolved.</p>
                  </div>
                  <div id="menu4" class="tab-pane fade"><br>
                    <h3 class="font-weight-bold txtblk mb-2">4. Convenient & Time-Efficient</h3>
                    <p class="paragraph mb-2">- Users are able to search and filter according to their needs and preference with just one-click away. </p>
                    <p class="paragraph mb-2">- Users are able to view all the Contractors and select according to their preference. </p>
                  </div>
                  <div id="menu5" class="tab-pane fade"><br>
                    <h3 class="font-weight-bold txtblk mb-2">5. Safe and Secure</h3>
                    <p class="paragraph mb-2">- Although Users liaise directly with the Contractors, they are still bound by Terms & Conditions to safeguard both Contractors’ and Users’ concerns. </p>
                        <p class="paragraph mb-2">- For any payment related matters, Rhinosite also has payment gateway incorporated to ensure that your payments are dealt in a secure and convenient way. </p>
                  </div>
                  <div id="menu6" class="tab-pane fade"><br>
                    <h3 class="font-weight-bold txtblk mb-2">6. Build a Long-Term Relationship with your Users/ Grow your Business</h3>
                    <p class="paragraph mb-2">- Once a User is satisfied with your services, they are more than happy to come back to you the next time they are looking for a Contractor. </p>
                    <p class="paragraph mb-2">- Users will also be delighted to introduce you to their friends or families who are looking for a reliable & trustable Contractor. </p>
                  </div>
                </div>
                <!-- Nav pills -->
                <ul class="nav nav-pills about-pills justify-content-lg-start justify-content-center" role="tablist">
                  <li class="nav-item pl-0">
                    <a class="nav-link active" data-toggle="pill" href="#menu1">1</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#menu2">2</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#menu3">3</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#menu4">4</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#menu5">5</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#menu6">6</a>
                  </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- <div id="about-2">
    <div class="container">
        <h2 class="text-center">Why Rhinosite?</h2>
        <div class="row align-items-center">
            <div class="col-lg-4 mx-auto">
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="font-weight-bold txtblk mb-2">1. Increase your exposure and presence online</p>
                        <p class="paragraph mb-0"> - Rhinosite allows you to grow your business by increasing the chances of your business exposure online – to connect property owners to contractors in a single platform.</p>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="font-weight-bold txtblk mb-2">2. Transparent & Hassle-Free</p>
                        <p class="paragraph mb-2">- By listing with us, contractors are able to provide their nature of business, past portfolios, location, contact numbers, asking price etc. so that the end users are able to filter according to their needs in a more time-efficient manner.</p>
                        <p class="paragraph mb-2">- No other hidden costs and commission charged. </p>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="font-weight-bold txtblk mb-2">3. Trustable & Reliable Contractors</p>
                        <p class="paragraph mb-2">- Rhinosite team will have a due diligence process to ensure that only reliable and quality contractors are able to be listed on our platform. </p>
                        <p class="paragraph mb-2">- Rhinosite will investigate thoroughly on those Contractors whom received poor ratings/ feedback. The Contractor will be terminated if the issue remains unsolved. </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-lg-block d-none">
                <img src="{{ asset('storage/assets/about/about-3.jpg') }}" alt="services_2_image" class="res-img px-4">
            </div>
            <div class="col-lg-4 mx-auto">
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="font-weight-bold txtblk mb-2">4. Convenient & Time-Efficient</p>
                        <p class="paragraph mb-2">- Users are able to search and filter according to their needs and preference with just one-click away. </p>
                        <p class="paragraph mb-2">- Users are able to view all the Contractors and select according to their preference. </p>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="font-weight-bold txtblk mb-2">5. Safe and Secure</p>
                        <p class="paragraph mb-2">- Although Users liaise directly with the Contractors, they are still bound by Terms & Conditions to safeguard both Contractors’ and Users’ concerns. </p>
                        <p class="paragraph mb-2">- For any payment related matters, Rhinosite also has payment gateway incorporated to ensure that your payments are dealt in a secure and convenient way. </p>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="font-weight-bold txtblk mb-2">6. Build a Long-Term Relationship with your Users/ Grow your Business</p>
                        <p class="paragraph mb-2">- Once a User is satisfied with your services, they are more than happy to come back to you the next time they are looking for a Contractor. </p>
                        <p class="paragraph mb-2">- Users will also be delighted to introduce you to their friends or families who are looking for a reliable & trustable Contractor. </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-lg-none d-block">
                <img src="{{ asset('storage/assets/about/about-2.PNG') }}" alt="services_2_image" class="res-img px-4">
            </div>
        </div>
    </div>
</div> --}}


@endsection