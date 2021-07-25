@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.partner')])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.partner_title_main') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="join-1">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 px-0">
                <img src="{{ asset('storage/assets/joinus/join-1.jpg') }}" alt="join_1_image" class="res-img">
            </div>
            <div class="col-md-6 col-lg-5 mx-auto text-center">
                <h2>
                    With the advance technology in today’s world, listing with Rhinosite enables you to
                    increase your exposure and presence online.
                </h2>
                <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-orange">{{ __('app.partner_btn_application') }}</a>
            </div>
        </div>
    </div>
</div>

<div id="join-2">
    <div class="container">
        <h2>Why Join Us?</h2>
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset('storage/assets/joinus/join-2.jpg') }}" alt="join_2_image_1" class="res-img mb-md-3 px-3">
                <p class="paragraph">1. Increase your exposure and presence online</p>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('storage/assets/joinus/join-3.jpg') }}" alt="join_2_image_1" class="res-img mb-md-3 px-3">
                <p class="paragraph">2. Convenient & Time-Efficient </p>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('storage/assets/joinus/join-4.jpg') }}" alt="join_2_image_1" class="res-img mb-md-3 px-3">
                <p class="paragraph">3. Build a Long-Term Relationship with your Users/ Grow your Business</p>
            </div>
        </div>
    </div>
</div>

<div id="join-3">
    <div class="container">
        <h2>What is Required</h2>
        <div class="row">
            <div class="col-md-3">
                <img src="{{ asset('storage/assets/joinus/join-5.png') }}" alt="join_light_bulb" class="join-icon">
                <p class="paragraph">1. Fill in an <a href="{{ route('register', ['role' => 'merchant']) }}">Electronic Application form</a></p>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('storage/assets/joinus/join-6.png') }}" alt="join_light_bulb" class="join-icon">
                <p class="paragraph">
                    2. Our team will take approximately 5-7 working days to process your application. (This is
                    a Rhinosite due-diligence process to ensure that our contractors are trustable and
                    reliable for their potential customers)
                </p>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('storage/assets/joinus/join-7.png') }}" alt="join_light_bulb" class="join-icon">
                <p class="paragraph">
                    3. You’ll be receiving an email on logging into your admin dashboard and complete your
                    purchase on the listing packages.
                </p>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('storage/assets/joinus/join-8.png') }}" alt="join_light_bulb" class="join-icon">
                <p class="paragraph">
                    4. You are all set! You can start to edit your profile and upload your projects and start
                    receiving enquiries from your potential customers.
                </p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <p class="paragraph">
                    * Note: You may also read up on our FAQ and Terms & Conditions incorporated herein to
                    understand our operation better.
                    <br>
                    If in any circumstances that you are still in doubt, we do have a support team to further
                    assist you with your queries.
                    <br>
                    Please drop us an email at info@rhinosite.com.my or call us
                    at 016-303 1808.
                </p>
            </div>
        </div>
    </div>
</div>

<div id="join-4">
    <div class="container">
        <h2>Steps to Register / Sign-Up</h2>
        <div class="join-bar"></div>
        <div class="row text-xl-center text-left">
            <div class="col-12 col-xl">
                <p class="paragraph">1. If you are new to Rhinosite, you may be thinking “How does Rhinosite work?” and “Where do I sign up?”. </p>
            </div>
            <div class="col-12 col-xl">
                <p class="paragraph">2. Well, once you have decided to join our platform, there is an application form here for you to fill in your details and our team will take approximately 5-7 working days to process your application. </p>
            </div>
            <div class="col-12 col-xl">
                <p class="paragraph">3. This is a Rhinosite due-diligence process to ensure that our contractors are trustable and reliable for their potential customers.</p>
            </div>
            <div class="col-12 col-xl">
                <p class="paragraph">4. You may also read up on our <a href="#join-5">FAQ</a> and <a href="tnc.html" target="blank">Terms & Conditions</a> incorporated herein to understand our operation better. </p>
            </div>
            <div class="col-12 col-xl">
                <p class="paragraph">5. If in any circumstances that you are still in doubt, we do have a support team to further assist you with your queries. Please drop us an email at info@rhinosite.com.my or call us at 016-303 1808. </p>
            </div>
        </div>
    </div>
</div>

<div id="join-5">
    <div class="container">
        <h2>FAQ</h2>
        <div id="accordion">
            <div class="card card-flat">
                <div class="card-header" id="heading1">
                    <a class="btn collapsed" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        <span>1. Why do you need to submit certain documents prior to registration? </span>
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </a>
                </div>

                <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordion">
                    <div class="card-body">
                        To complete your registration process, you may need to submit certain documents pertaining to your company profile for us to conduct our due diligence check. This is to ensure our Contractors are trustable and reliable to improve Client’s experience. Please rest assured that
                        all information and documents provided shall be kept confidential between Contractors and Rhinosite.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading2">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            <span>2. How do you gauge your earning potential?</span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordion">
                    <div class="card-body">
                        Rhinosite intends to provide a hassle-free process when comes to End Users looking for a contractors for any renovation works. Rhinosite shall not interfere on any communication made between Contractors & Clients. However, do keep in mind that how much Contractors can earn
                        through Rhinosite depends of several factors such as your targeted area of services, pricing, location, past projects, ratings & reviews etc.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading3">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            <span>3. How do we maximise your target audiences? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
                    <div class="card-body">
                        Rhinosite is targeted to be Malaysia’s No. 1 Digital Contractor Site for the benefit of both End Users and Contractors. Our core value is to provide a transparent and hassle free process when comes to any Building works. Our responsible is to uphold our core value by
                        educating users on how to fully utilise the function of Rhinosite. How do we achieve that? By showing and proving the qualities and values of Rhinosite on available sources – to educate users that ‘whenever they think about looking for a suitable and quality contractor,
                        eventually they will think about Rhinosite.’
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading4">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            <span>4. What if home-owners did not pay? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
                    <div class="card-body">
                        By accessing Rhinosite and/ or using the Services provided by our Contractors, they agree to be bound by our Terms. The actual contract is directly between the Contractor and End-User, hence the Contractors may take legal action against the Defaulting Party.
                        <br>
                        You may refer to our <a href="tnc.html">Terms & Conditions</a> incorporated herein for better understanding.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading5">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                            <span>5. What is Rhinosite providing to the Contractors? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body">
                        Rhinosite provides a platform for the Contractors to promote and/ or offer its services to the End-Users. We also provide supporting services that enable your listing and publishing your content.
                        <br>
                        Contractor is required to select one category from the sector classification list on the Platform based on the types of services they offer. For payment of the monthly subscription fees, please refer to the <a href="">pricing list</a> for further information.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading6">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                            <span>6. How do Contractors stand out from their competitors? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordion">
                    <div class="card-body">
                        Rhinosite intends to help Contractors to grow their business by expanding their online exposure. The Contractors may purchase the ‘Category Bumping’, ‘Category Highlight’ and/ or ‘Banner Advertisement’ to attract more attention from the End Users. For payment of these
                        additional features, please refer to the <a href="#">pricing list</a> for your kind reference.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading7">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                            <span>7. What is Listing Category? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#accordion">
                    <div class="card-body">
                        Our fellow contractors are listed according to their area of services/speciality. For example, flooring, painting, wiring, gate, alarm & security etc.
                        <br>
                        Your company details, past projects and other accreditations will also be displayed & available to create a transparent & time-efficient process for the end users.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading7">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                            <span>8. What is Category Bumping? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse8" class="collapse" aria-labelledby="heading7" data-parent="#accordion">
                    <div class="card-body">
                        Contractors are able to bump up their company's listing to the first top 3 listing in first page of the Listing Category.
                        There are three (3) Category Bump slots available each week, so book an secure your slots in advance.
                        <a href="{{ route('register', ['role' => 'merchant']) }}"> Click here</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading8">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
                            <span>9. What is Category Highlight? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse9" class="collapse" aria-labelledby="heading8" data-parent="#accordion">
                    <div class="card-body">
                        In addition to the above, Category highlight allows contractors to move up their company listing onto the first page of the Listing Category.
                        However, do keep in mind that these Category Highlights are arranged in random sequence (unlike the Category Bump). Simlarly, there are three (3) Category Highlights slots available each week,
                        so book and secure your slots as well!
                        <a href="{{ route('register', ['role' => 'merchant']) }}"> Click here</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading9">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
                            <span>10. What is Banner Advertisement? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse10" class="collapse" aria-labelledby="heading9" data-parent="#accordion">
                    <div class="card-body">
                        Banner Advertisement allows Contractors to advertise their company and services on Rhinosite to increase their visibility.
                        There are eight (8) slots available each week, so book and secure your slots as soon as possible!
                        <a href="{{ route('register', ['role' => 'merchant']) }}"> Click here</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading10">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse11" aria-expanded="false" aria-controls="collapse11">
                            <span>11. How does Contractor subscribe or renew their subscription with Rhinosite? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse11" class="collapse" aria-labelledby="heading10" data-parent="#accordion">
                    <div class="card-body">
                        All fee for subscription and renewal can be made using various payment methods available, subject to the Terms & Conditions. The said subscription payment shall be auto debited monthly based on the chosen Subscription plan and shall be due and payable from the commencement
                        date of the Subscription Term.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading11">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12">
                            <span>12. Can a Contractor get their refund upon paying my subscription and Add-Ons? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse12" class="collapse" aria-labelledby="heading11" data-parent="#accordion">
                    <div class="card-body">
                        You may apply for a refund of the Subscription Fee and/or fee paid for the Add-Ons by writing to us in the event En Vivo fails to provide you with the Services or the Add-Ons in accordance with these Terms. Any amount to be refunded to the Contractor and manner of such refund
                        is subject to the approval and discretion of En Vivo.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading12">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13">
                            <span>13. What type of services do Rhinosite provide? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse13" class="collapse" aria-labelledby="heading12" data-parent="#accordion">
                    <div class="card-body">
                        Rhinosite allows Contractors to list their services based on their areas of specialties up to <span class="text-danger">XX (to insert CIDB code if got)</span>. Contractors hereby expressly agrees, covenants and undertakes that it shall at all times, use their best endeavours
                        to prepare and complete all relevant building works required by the End users.
                        <br>
                        You may refer to our <a href="tnc.html">Terms & Conditions</a> on Contractor’s Standard of Services incorporated herein for better understanding.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading13">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse14" aria-expanded="false" aria-controls="collapse14">
                            <span>14. After my subscription has expired, how do the Contractor terminate their account? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse14" class="collapse" aria-labelledby="heading13" data-parent="#accordion">
                    <div class="card-body">
                        The Contractors can always write to us if they wish to terminate their account when their subscription is nearing expiry. Upon termination, your content on Rhinosite will be removed from the platform.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading14">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse15" aria-expanded="false" aria-controls="collapse15">
                            <span>15. What if a Contractor intend to terminate my account right after they have subscribed to a Subscription plan? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse15" class="collapse" aria-labelledby="heading14" data-parent="#accordion">
                    <div class="card-body">
                        The Contractors can always write to us if they wish to terminate their account when their subscription is nearing expiry. Upon termination, your content on Rhinosite will be removed from the platform. However the Contractor shall pay to En Vivo the Subscription Fee for the
                        entire Subscription Term (which for the avoidance of doubt, includes the remaining months in the Subscription Term and payable in a lump sum.)
                        <br>
                        You may refer to our <a href="tnc.html">Terms & Conditions</a> on Termination incorporated herein for better understanding.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading15">
                    <h5 class="mb-0">
                        <a class="btn collapsed" data-toggle="collapse" data-target="#collapse16" aria-expanded="false" aria-controls="collapse16">
                            <span>16. What can a Contractor post as content and what should not? </span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                    </h5>
                </div>
                <div id="collapse16" class="collapse" aria-labelledby="heading15" data-parent="#accordion">
                    <div class="card-body">
                        You shall not post, display or disclose any materials which may infringe the Terms and/ or the law. Rhinosite may unilaterally and immediately terminate your use of the platform if you are found in breach of these Terms. For more information on content restriction, kindly
                        find more on <a href="tnc.html">Section 8 of the Terms of Service</a> for the Contractors.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection