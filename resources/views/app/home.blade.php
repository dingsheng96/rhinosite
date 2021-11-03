@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.home')])

@section('content')

<div id="new-home-s1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left ml-auto">
                <h1>
                    {{ __('app.home_title_main') }}
                </h1>
                <h2 class="home-s1-subtitle text-white"><small>{{ __('app.home_subtitle_main') }}</small></h2>
                <a href="{{ route('app.management') }}" class="btn btn-round px-5">Ask Us How</a>
            </div>
        </div>
    </div>
</div>

{{-- <div id="home-category">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="category-title">{{ __('app.top_search_services') }}</p>
            </div>

            @forelse ($services->take(4) as $service)
            <div class="col-md-6 col-lg-3 col-xs-12 d-inline-flex">
                <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="category-item">
                    <img src="{{ asset('assets/home/icon1.png') }}" alt="shopping_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>{{ strtoupper($service->name) }}</span>
                </a>
            </div>
            @empty
            <div class="col-12 d-inline-flex">&nbsp;</div>
            @endforelse

        </div>
    </div>
</div> --}}

{{-- <div id="home-whyrhino">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <h2 class="text-center d-lg-none d-block">Why Rhinosite?</h2>
                <img src="{{ asset('assets/about/whyrhinosite.jpg') }}" class="res-img mb-3 mb-lg-0">
            </div>
            <div class="col-lg-7 pl-lg-5">
                <h2 class="mb-0 d-lg-block d-none">Why Rhinosite?</h2>
                <!-- Tab panes -->
                <div class="tab-content about-tabbed text-lg-left text-center">
                    <div id="menu1" class="tab-pane active"><br>
                        <h3 class="font-weight-bold txtblk mb-2">1. Increase your exposure and presence online</h3>
                        <p class="paragraph mb-0">Rhinosite allows you to grow your business by increasing the chances of your business exposure online – to connect property owners to contractors in a single platform.</p>
                    </div>
                    <div id="menu2" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">2. Transparent & Hassle-Free</h3>
                        <p class="paragraph mb-2">By listing with us, contractors are able to provide their nature of business, past portfolios, location, contact numbers, asking price etc. so that the end users are able to filter according to their needs in a more time-efficient manner.</p>
                        <p class="paragraph mb-2">No other hidden costs and commission charged. </p>
                    </div>
                    <div id="menu3" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">3. Trustable & Reliable Contractors</h3>
                        <p class="paragraph mb-2">Rhinosite team will have a due diligence process to ensure that only reliable and quality contractors are able to be listed on our platform.</p>
                        <p class="paragraph mb-2">Rhinosite will investigate thoroughly on those Contractors whom received poor ratings/ feedback. The Contractor will be terminated if the issue remains unsolved.</p>
                    </div>
                    <div id="menu4" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">4. Convenient & Time-Efficient</h3>
                        <p class="paragraph mb-2">Users are able to search and filter according to their needs and preference with just one-click away. </p>
                        <p class="paragraph mb-2">Users are able to view all the Contractors and select according to their preference. </p>
                    </div>
                    <div id="menu5" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">5. Safe and Secure</h3>
                        <p class="paragraph mb-2">Although Users liaise directly with the Contractors, they are still bound by Terms & Conditions to safeguard both Contractors’ and Users’ concerns. </p>
                        <p class="paragraph mb-2">For any payment related matters, Rhinosite also has payment gateway incorporated to ensure that your payments are dealt in a secure and convenient way. </p>
                    </div>
                    <div id="menu6" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">6. Build a Long-Term Relationship with your Users/ Grow your Business</h3>
                        <p class="paragraph mb-2">Once a User is satisfied with your services, they are more than happy to come back to you the next time they are looking for a Contractor. </p>
                        <p class="paragraph mb-2">Users will also be delighted to introduce you to their friends or families who are looking for a reliable & trustable Contractor. </p>
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

<div id="home-tryrhino">
    <div class="container">
        <div class="row">
            <div class="col-12 try-rhino-background">
                <div class="row">
                    <div class="col-lg-6 try-rhino-content">
                        <h2 class="text-white">Try Rhinosite</h2>
                        <p class="paragraph text-white">Subscribe with us today to create more business opportunities and build a long-term relationship with your clients via a hassle-free & transparent process.</p>
                        <a href="{{ route('app.partner') }}" class="btn btn-round">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div id="home-s2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2><small class="font-semibold">{{ __('app.home_subtitle_steps') }}</small></h2>
            </div>
            <div class="col-12 text-center">
                <div class="slider slider-steps-title">
                    <div>
                        <p class="home-steps-title mb-3">Step 1 – Knowing your needs, and let us understand you better</p>
                    </div>
                    <div>
                        <p class="home-steps-title mb-3">Step 2 – Customizing a design plan</p>
                    </div>
                    <div>
                        <p class="home-steps-title mb-3">Step 3 – Meeting you in person</p>
                    </div>
                    <div>
                        <p class="home-steps-title mb-3">Step 4 – Appointing a Project Manager</p>
                    </div>
                    <div>
                        <p class="home-steps-title mb-3">Step 5 – Working towards your dream home</p>
                    </div>
                </div>
                <div class="slider slider-steps">
                    <div>
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8">
                                <p class="paragraph txtblk">Get a consultation with our experts to understand your personality, from there we could better understand your needs and requirements for your home. </p>
                                <img src="{{ asset('assets/home/new/step1.png') }}" alt="step-1" class="res-img">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8">
                                <p class="paragraph txtblk">After reviewing your initial requirements & needs, we will prepare a few proposed designs/ layout plan for you. </p>
                                <img src="{{ asset('assets/home/new/step2.png') }}" alt="step-1" class="res-img">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8">
                                <p class="paragraph txtblk">We will meet you personally to finalised the proposed designs and walk you through our processes moving forward. </p>
                                <img src="{{ asset('assets/home/new/step3.png') }}" alt="step-1" class="res-img">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8">
                                <p class="paragraph txtblk">Your appointed project manager will then work with you from the start till completion of your project, together with the chosen contractor from a non-exhaustive list from Rhinosite. </p>
                                <img src="{{ asset('assets/home/new/step4.png') }}" alt="step-1" class="res-img">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-8">
                                <p class="paragraph txtblk">Upon finalising the layout plan and a detailed timeline on your project, we will work closely with the chosen RhinoContractor and overlook the entire progress on site until meeting your satisfaction. </p>
                                <img src="{{ asset('assets/home/new/step5.png') }}" alt="step-1" class="res-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-scope">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-8 pr-lg-5 mb-5 mb-lg-0">
                <h2 class="text-center text-lg-left">{{ __('app.home_subtitle_scope') }}</h2>
                <p class="paragraph txtblk">{{ __('app.home_subtitle_scope_content') }}</p>
                <ul>
                    <li class="paragraph txtblk mb-3">Transforming the way things are traditionally done for your home improvement</li>
                    <li class="paragraph txtblk mb-3">Customising interior design based on your requirements and style of living</li>
                    <li class="paragraph txtblk mb-3">Plan & execute according to your budget</li>
                    <li class="paragraph txtblk mb-3">Compare & choose your desire RhinoContractors</li>
                </ul>
            </div>
            <div class="col-md-7 col-lg-4">
                <div class="row">
                    <div class="col-6">
                        <img src="{{ asset('assets/home/new/icon1.png') }}" alt="step-1" class="res-img">
                        <img src="{{ asset('assets/home/new/icon2.png') }}" alt="step-1" class="res-img">
                    </div>
                    <div class="col-6 mt-4">
                        <img src="{{ asset('assets/home/new/icon3.png') }}" alt="step-1" class="res-img">
                        <img src="{{ asset('assets/home/new/icon4.png') }}" alt="step-1" class="res-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-job">
    <div class="container text-center">
        <h2>{{ __('app.home_subtitle_job') }}</h2>
        <p class="paragraph txtblk mb-5">{{ __('app.home_subtitle_job_content') }}</p>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-4 position-relative mb-4">
                <img src="{{ asset('assets/home/new/job1.png') }}" class="res-img">
                <div class="home-job-text">
                    <div>
                        <h2 class="text-white">RESIDENTIAL</h2>
                        <p class="paragraph mb-0 text-white">Home, Serviced apartments, Condominiums</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-4 position-relative mb-4">
                <img src="{{ asset('assets/home/new/job2.png') }}" class="res-img">
                <div class="home-job-text">
                    <div>
                        <h2 class="text-white">COMMERCIAL</h2>
                        <p class="paragraph mb-0 text-white">Shoplots, Office Spaces</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-4 position-relative mb-4">
                <img src="{{ asset('assets/home/new/job3.png') }}" class="res-img">
                <div class="home-job-text">
                    <div>
                        <h2 class="text-white">SEMI</h2>
                        <p class="paragraph mb-0 text-white">Home offices</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-s3">
    <img src="{{ asset('assets/home/new/contactus.png') }}" alt="s3_image" class="home-s3-left">
    <div class="home-s3-right">
        <h2>{!! __('app.home_text_join_merchant') !!}</h2>
        <p class="paragraph text-white">{!! __('app.home_subtext_join_merchant') !!}</p>
        <a href="{{ route('app.contact') }}" class="btn btn-round">{{ __('app.home_btn_join_merchant') }}</a>
    </div>
</div>

<div id="home-s4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center">{{ __('app.home_subtitle_partner') }}</h2>
            </div>
            <div class="col-lg-8">
                <p class="paragraph txtblk">
                    Rhinosite emphasize on quality works and only contractors that fulfilled our due diligence process are able to be listed.
                </p>
            </div>
            <div class="col-lg-10">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="slider slider slider-home">
                            @forelse ($merchants as $merchant)
                            <div>
                                <img src="{{ $merchant->media->first()->full_file_path ?? $default_preview }}" alt="partner_image_1" class="home-s4-img">
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-recommendation">
    <div class="container">
        <h2 class="text-center">Rhinosite Recommendations</h2>
        <p class="paragraph txtblk text-center">Need help to look for a RhinoContractor? See what our happy users say about them.</p>
        <div class="slider slider-recommendation">
            <div>
                <div class="recommendation-card">
                    <div class="d-block text-center text-sm-left d-sm-flex align-items-center mb-4">
                        <div class="recommendation-profile-icon mb-4 mb-sm-0 mx-auto mx-sm-0">
                            JW
                        </div>
                        <div class="pl-sm-3">
                            <h3 class="mb-2 font-weight-bold txtblk">Jason Wong</h3>
                            <small class="paragraph txtblk">Kuala Lumpur</small>
                        </div>
                    </div>
                    <p class="paragraph txtblk">I recently found out that my home aircons needed some maintenance as it has been there for years. Thanks to Rhinosite’s Contractors, I am able to find reliable and trustable Contractor to carry out the necessary works. Prices are reasonable as
                        well!
                    </p>
                </div>
            </div>
            <div>
                <div class="recommendation-card">
                    <div class="d-block text-center text-sm-left d-sm-flex align-items-center mb-4">
                        <div class="recommendation-profile-icon mb-4 mb-sm-0 mx-auto mx-sm-0">
                            JT
                        </div>
                        <div class="pl-sm-3">
                            <h3 class="mb-2 font-weight-bold txtblk">Joanna Tan</h3>
                            <small class="paragraph txtblk">Selangor</small>
                        </div>
                    </div>
                    <p class="paragraph txtblk">I love how Rhinosite is a one-stop platform for all of my concerns. I was able to compare prices and check through their past projects with just a click away. I was also able to be in touch with the Contractors directly to speed up the process.
                        They
                        have
                        quick turnaround response too! </p>
                </div>
            </div>
            <div>
                <div class="recommendation-card">
                    <div class="d-block text-center text-sm-left d-sm-flex align-items-center mb-4">
                        <div class="recommendation-profile-icon mb-4 mb-sm-0 mx-auto mx-sm-0">
                            EW
                        </div>
                        <div class="pl-sm-3">
                            <h3 class="mb-2 font-weight-bold txtblk">Eu Wen</h3>
                            <small class="paragraph txtblk">Kuala Lumpur</small>
                        </div>
                    </div>
                    <p class="paragraph txtblk">I am very happy to found a trustable Contractor through Rhinosite! Definitely recommend this site!</p>
                </div>
            </div>
            <div>
                <div class="recommendation-card">
                    <div class="d-block text-center text-sm-left d-sm-flex align-items-center mb-4">
                        <div class="recommendation-profile-icon mb-4 mb-sm-0 mx-auto mx-sm-0">
                            MC
                        </div>
                        <div class="pl-sm-3">
                            <h3 class="mb-2 font-weight-bold txtblk">Melvin Chong</h3>
                            <small class="paragraph txtblk">Sabah</small>
                        </div>
                    </div>
                    <p class="paragraph txtblk">Prompt, Reasonable, Trustable & Quality – Thank you LNL Integrated for your painting services! </p>
                </div>
            </div>
            <div>
                <div class="recommendation-card">
                    <div class="d-block text-center text-sm-left d-sm-flex align-items-center mb-4">
                        <div class="recommendation-profile-icon mb-4 mb-sm-0 mx-auto mx-sm-0">
                            AT
                        </div>
                        <div class="pl-sm-3">
                            <h3 class="mb-2 font-weight-bold txtblk">Alvin Teh </h3>
                            <small class="paragraph txtblk">Selangor</small>
                        </div>
                    </div>
                    <p class="paragraph txtblk">Hassle-free & cheaper prices compared to other quotations that I have enquired. Highly recommend Rhinosite if you are looking for a Project Management Team to manage your renovations. </p>
                </div>
            </div>
            <div>
                <div class="recommendation-card">
                    <div class="d-block text-center text-sm-left d-sm-flex align-items-center mb-4">
                        <div class="recommendation-profile-icon mb-4 mb-sm-0 mx-auto mx-sm-0">
                            DL
                        </div>
                        <div class="pl-sm-3">
                            <h3 class="mb-2 font-weight-bold txtblk">Dilys Lai</h3>
                            <small class="paragraph txtblk">Selangor</small>
                        </div>
                    </div>
                    <p class="paragraph txtblk">I have recently bought a house in Bukit Jalil and wanted to furnished it up but I was concerned about Covid-19 since the lockdown has just lifted. However, thanks to Rhinosite Project Management Team who carefully planned everything and made
                        sure all
                        their
                        Contractors are fully vaccinated and adhere to SOP while carrying out their work. Professional and Quality! I am so happy to be able to move into my new home without spending a bomb on the renovation costs. </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection