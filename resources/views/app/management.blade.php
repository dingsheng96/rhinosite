@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.howitworks')])

@section('content')

<div id="management-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.management_title_main') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="management-summary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <h2 class="text-white text-center">{{ __('app.management_summary') }}</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="slider slider-management">
                    <div class="management-summary-content">
                        <div class="row justify-content-center pb-3 mb-3 border-bottom">
                            <div class="col-md-10">
                                <p class="paragraph mb-0 text-center text-white">{{ __('app.management_summary_content') }}</p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <p class="manamgement-summary-title">i.</p>
                                    <p class="paragraph text-white mb-0">Building your own paradise is all about your desire and fantasy. It often begins with a thought, moving onto some planning, budgeting, and finalising everything up. Finally, let Rhinosite help you to turn that dream into a
                                        reality.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="management-summary-content">
                        <div class="row justify-content-center pb-3 mb-3 border-bottom">
                            <div class="col-md-10">
                                <p class="paragraph mb-0 text-center text-white">{{ __('app.management_summary_content') }}</p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <p class="manamgement-summary-title">ii.</p>
                                    <div>
                                        <p class="paragraph text-white mb-0">So, what are you still waiting for? Tell us about your dream home today.</p>
                                        <ul class="mt-3">
                                            <li>Your needs </li>
                                            <li>Your style of living</li>
                                            <li>Your expectation</li>
                                            <li>Budget</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="management-love-rhinosite">
    <div class="container-fluid">
        <h2 class="text-center">{{ __('app.management_love_rhinosite_title') }}</h2>
        <div class="row align-items-lg-center bg-orange">
            <div class="col-md-6 px-0 py-3 d-inline-flex flex-column order-md-1 order-2">
                <h2 class="text-center">{{ __('app.management_love_rhinosite_subtitle1') }}</h2>
                <p class="paragraph txtblk px-5 mb-4">We emphasise in understanding you better before telling you how we can move forward together. The more we know about you, the better we could deliver in building your home. Our initial consultations are always free to identify your needs. </p>
            </div>
            <div class="col-md-6 p-0 d-inline-flex order-md-2 order-1">
                <img src="{{ asset('assets/management/wecare.png') }}" class="res-img">
            </div>
        </div>
        <div class="row align-items-lg-center bg-black">
            <div class="col-md-6 p-0 d-inline-flex">
                <img src="{{ asset('assets/management/hasslefree.png') }}" class="res-img">
            </div>
            <div class="col-md-6 px-0 py-3 d-inline-flex flex-column">
                <h2 class="text-white text-center">{{ __('app.management_love_rhinosite_subtitle2') }}</h2>
                <p class="paragraph text-white px-5 mb-4">With the support of our web-based platform, you may be able to choose and compare from a wide range of contractors’ portfolio. With every project, we strictly emphasize on quality result and ensure that each client is satisfied with the
                    service
                    offered. </p>
            </div>
        </div>
        <div class="row align-items-lg-center bg-orange">
            <div class="col-md-6 px-0 py-3 d-inline-flex flex-column order-md-1 order-2">
                <h2 class="text-center">{{ __('app.management_love_rhinosite_subtitle3') }}</h2>
                <p class="paragraph txtblk px-5 mb-4">Contractors are carefully chosen to fulfil your requirements. We are an experience team that work hand-in-hand with licensed contractors on residential and commercial projects. We strive to provide the best towards our clients in terms of
                    quality,
                    work during
                    progress, timeline,
                    budget and most importantly, the end result. </p>
            </div>
            <div class="col-md-6 p-0 d-inline-flex order-md-2 order-1">
                <img src="{{ asset('assets/management/customerfirst.png') }}" class="res-img">
            </div>
        </div>
    </div>
</div>

<div id="management-issue">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
                <img src="{{ asset('assets/management/problems.png') }}" class="res-img mb-3 mb-lg-0">
            </div>
            <div class="col-xl-7 pl-xl-5">
                <h2 class="font-weight-bold txtblk text-center">{{ __('app.management_issue_title') }}</h2>
                <div class="row">
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-orange first">01</span>
                        <p class="paragraph txtblk">Unnecessary penalty due to work without authority approval</p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-orange second">02</span>
                        <p class="paragraph txtblk">Contractors being unreliable & untrustworthy</p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-orange third">03</span>
                        <p class="paragraph txtblk">Unexpected hidden charges </p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-orange">04</span>
                        <p class="paragraph txtblk">Project timeline delayed</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-black first">05</span>
                        <p class="paragraph txtblk">Overpriced </p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-black second">06</span>
                        <p class="paragraph txtblk">Overpromise </p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-black third">07</span>
                        <p class="paragraph txtblk">Opaque transaction due to work involving more than one party</p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center mb-3 mb-md-0">
                        <span class="issue-number-black">08</span>
                        <p class="paragraph txtblk">Defect liability period unclear</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="management-steps">
    <div class="container">
        <div class="mb-5">
            <div class="position-relative">
                <img src="{{ asset('assets/management/step1.png') }}" class="management-steps-img">
                <div class="steps-content">
                    <h2 class="txtorange mb-1">Step 1</h2>
                    <h3 class="text-white font-semibold management-subtitle">Knowing yourself better leads you to understand your needs better. </h3>
                    <p class="paragraph text-white">Let’s start off by answering a few questions that allow us to understand you better and your definition of home. This helps us to enhance our designing process as we can design your dream home best suit your personality, style and character.</p>
                </div>
            </div>
        </div>
        <div class="position-relative mb-5">
            <img src="{{ asset('assets/management/step2.png') }}" class="management-steps-img">
            <div class="steps-content orange">
                <h2 class="mb-1">Step 2</h2>
                <h3 class="text-white font-semibold management-subtitle">Proposing a plan suited your needs </h3>
                <p class="paragraph txtblk">This will be the designing stage where our panels architects/ interior designers will design a few samples for your consideration. </p>
            </div>
        </div>
        <div class="position-relative mb-5">
            <img src="{{ asset('assets/management/step3.png') }}" class="management-steps-img">
            <div class="steps-content">
                <h2 class="txtorange mb-1">Step 3</h2>
                <h3 class="text-white font-semibold management-subtitle">Meet & greet session </h3>
                <p class="paragraph text-white">We will meet you personally to discuss and finalised the designs and walk you through our processes moving forward. </p>
            </div>
        </div>
        <div class="position-relative mb-5">
            <img src="{{ asset('assets/management/step4.png') }}" class="management-steps-img">
            <div class="steps-content orange">
                <h2 class="txtblk mb-1">Step 4</h2>
                <h3 class="text-white font-semibold management-subtitle">Appointing a Project Manager just for you</h3>
                <ul class="txtblk">
                    <li class="paragraph txtblk">A project manager will be appointed specifically for you after understanding all your requirements and needs. Your appointed project manager will in charge of your project from start till completion. </li>
                    <li class="paragraph txtblk">Your appointed project manager will also prepare a non exhaustive list of contractors that will be working on your project and an estimated timeline.</li>
                    <li class="paragraph txtblk">This is where we will work with our RhinoContractors. Each and every sub-contractor chosen for you will provide the best quality & services for your project. However, you will have the right to modify the contractors list to your satisfaction if
                        required. </li>
                </ul>
            </div>
        </div>
        <div class="position-relative">
            <img src="{{ asset('assets/management/step5.png') }}" class="management-steps-img">
            <div class="steps-content">
                <h2 class="txtorange mb-1">Step 5</h2>
                <h3 class="text-white font-semibold management-subtitle">Commencing construction work </h3>
                <p class="paragraph text-white">Finally, we will finalise your chosen design and timeline based on what we agreed on. We will work closely with the chosen RhinoContractors and overlook the entire progress on site until meeting your satisfaction. </p>
            </div>
        </div>
    </div>
</div>

<div id="management-enquiry">
    <div class="position-relative">
        <img src="{{ asset('assets/management/enquiry.png') }}" class="res-img">
        <div class="enquiry-content">
            <h2 class="text-white">How RhinoProject Management works</h2>
            <a href="{{ __('app.management_enquiry') }}" class="btn btn-round rounded-pill">Send us your enquiry now</a>
        </div>
    </div>
</div>

{{-- <div id="about-1">
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
                <img src="{{ asset('assets/logo-footer.png') }}" alt="about_3_image" class="res-img">
            </div>
        </div>
    </div>
</div>


<div id="about-2">
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
</div> --}}

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
                <img src="{{ asset('assets/about/about-3.jpg') }}" alt="services_2_image" class="res-img px-4">
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
                <img src="{{ asset('assets/about/about-2.PNG') }}" alt="services_2_image" class="res-img px-4">
            </div>
        </div>
    </div>
</div> --}}


@endsection