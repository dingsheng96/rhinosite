@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.merchant_profile')])

@section('content')

{{-- <div id="profile-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.merchant_title_main') }}</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 position-relative">
                    @include('app.search')
                </div>
            </div>
        </div>
    </div>
</div> --}}

@include('app.topservice')

<div id="profile-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('app.home') }}">{{ __('modules.app.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('app.project.index') }}">{{ __('modules.app.merchant') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $merchant->name }}</li>
                </ol>
            </div>
        </div>
        {{-- <div class="profile-card">
            <div class="col-lg-7 col-xl-8 align-self-center">
                <img src="{{ $merchant->logo->full_file_path }}" alt="merchant_profile_pic" class="profile-img">
                <span class="profile-name">{{ $merchant->name }}</span>
            </div>
            <div class="col-lg-5 col-xl-4 text-right align-self-center">
                <p>{{ __('app.merchant_joined_date', ['date' => $merchant->joined_date]) }}</p>
                <p>{{ __('app.merchant_industry_year', ['year' => trans_choice('labels.year', $merchant->userDetail->years_of_experience, ['value' => $merchant->userDetail->years_of_experience])]) }}</p>
                <p id="rating-stars">{!! $merchant->rating_stars !!}</p>

                @auth
                @member
                <button type="button" class="btn btn-orange btn-rate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('app.merchant_btn_rate_merchant') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right merchant-rate text-center">
                    <form action="{{ route('app.ratings.store') }}" method="POST" role="form" enctype="multipart/form-data" id="ratingform" data-merchant="{{ $merchant->id }}">
                        <p class="mb-0">{{ __('app.merchant_rate_dropdown_title') }}</p>
                        <div class="rate">
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5"></label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4"></label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3"></label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2"></label>
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1"></label>
                        </div>
                    </form>
                </div>
                @endmember
                @endauth

                @guest
                <a role="button" href="{{ route('login') }}" class="btn btn-orange" aria-haspopup="true" aria-expanded="false">
                    {{ __('app.merchant_btn_login_rate') }}
                </a>
                @endguest
            </div>
            <div class="profile-line"></div>
            <div class="col-lg-8">
                <div class="d-flex mb-3 align-items-center"><i class="fas fa-phone profile-icon" aria-hidden="true"></i><span class="ml-3">{{ $merchant->formatted_phone_number }}</span></div>
                <div class="d-flex mb-3 align-items-center"><i class="fas fa-map-marker-alt profile-icon location" aria-hidden="true"></i><span class="ml-3">{{ $merchant->address->full_address }}</span></div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex mb-3 align-items-center text-break"><i class="fas fa-envelope profile-icon mail" aria-hidden="true"></i><span class="ml-3">{{ $merchant->email }}</span></div>
                @if(!empty($merchant->userDetail->website))
                <a href="{{ $merchant->userDetail->website }}" class="d-flex mb-3 align-items-center text-break text-decoration-none txtgrey"><i class="fas fa-globe profile-icon mail" aria-hidden="true"></i><span class="ml-3">{{ $merchant->userDetail->website }}</span></a>
                @endif
            </div>
        </div> --}}
        <div class="services-card py-4 px-4 px-md-5">
            <div class="d-lg-flex align-items-center">
                <img src="{{ $merchant->logo->full_file_path ?? $default_preview }}" alt="service_brand" class="services-img text-break">
                <div class="d-lg-flex align-items-start justify-content-between w-100">
                    <div class="ml-lg-4">
                        <p class="services-title font-weight-bold txtblk mt-4 mb-0">{{ $merchant->name }}</p>
                        <p class="paragraph mb-0">{{ __('app.merchant_joined_date', ['date' => $merchant->joined_date]) }}</p>
                        <p id="rating-stars">{!! $merchant->rating_stars !!}</p>
                    </div>
                    <div>
                        <p class="paragraph font-medium mb-3 mt-4">{{ __('app.merchant_industry_year', ['year' => trans_choice('labels.year', $merchant->userDetail->years_of_experience, ['value' => $merchant->userDetail->years_of_experience])]) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="services-card">
            <ul class="nav nav-tabs border-0 align-items-center d-block d-md-flex px-sm-4 py-sm-3" id="merchantprofile" role="tablist">
                <li class="nav-item">
                    <a class="nav-link border-0 mb-4 mb-md-0" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-0 mb-4 mb-md-0" id="gallery-tab" data-toggle="tab" href="#gallery" role="tab" aria-controls="gallery" aria-selected="false">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-0 mb-4 mb-md-0" id="recommendation-tab" data-toggle="tab" href="#recommendation" role="tab" aria-controls="recommendations" aria-selected="false">Recommendations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-0 mb-4 mb-md-0" id="rating-tab" data-toggle="tab" href="#rating" role="tab" aria-controls="ratings" aria-selected="false">Reviews & Ratings</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="services-card p-3">
                    <div class="tab-content" id="merchantProfileTab">
                        <div class="tab-pane fade px-3 py-4" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <div class="mb-5">
                                <h3>About This Contractor</h3>
                                {!! !empty($merchant->userDetail->about) ? $merchant->userDetail->about : '<p>No content found.</p>' !!}
                            </div>
                            <div class="mb-5">
                                <h3>Services</h3>
                                {!! !empty($merchant->userDetail->aboutservice) ? $merchant->userDetail->aboutservice : '<p>No content found.</p>' !!}
                            </div>
                            <div class="mb-5">
                                <h3>Experienced Team</h3>
                                {!! !empty($merchant->userDetail->team) ? $merchant->userDetail->team : '<p>No content found.</p>' !!}
                            </div>
                            <div>
                                <h3>Other to Take Note</h3>
                                {!! !empty($merchant->userDetail->other) ? $merchant->userDetail->other : '<p>No content found.</p>' !!}
                            </div>
                        </div>
                        <div class="tab-pane fade px-3 py-4 {{ request()->input('media') ? 'show active' : '' }}" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                            <h3>Contractor's Gallery</h3>
                            <div class="row">
                                @forelse ($medias as $media)
                                <div class="col-lg-6 mb-4">
                                    <img src="{{ $media->full_file_path }}" alt="{{ $media->original_filename }}" class="gallery-img">
                                </div>
                                @empty
                                <p>No image found.</p>
                                @endforelse
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    {!! $medias->fragment('gallery-tab')->links() !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade px-3 py-4" id="recommendation" role="tabpanel" aria-labelledby="recommendation-tab">
                            <h3>Contractor's Recommendations Work</h3>
                            <div class="row justify-content-start">

                                @forelse ($projects as $project)
                                <div class="col-12 col-xl-6 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show', ['project' => $project->id]) }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->user->name }}" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">{{ $project->english_title }}</p>
                                                {{-- <p class="merchant-title">{{ $project->chinese_title }}</p> --}}
                                                <p class="merchant-subtitle">{{ $project->user->name }}</p>
                                                <p class="merchant-subtitle">
                                                    <span class="badge badge-pill badge-info badge-padding">{{ $project->user->service->name ?? '-' }}</span>
                                                </p>
                                            </div>
                                            <div class="merchant-footer">
                                                {{-- <span class="merchant-footer-left">{{ __('app.price_from') . ' ' . $project->price_without_unit }}</span> --}}
                                                <span class="merchant-footer-right"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location }}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 d-inline-flex">{{ __('messages.no_records') }}</div>
                                @endforelse

                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    {!! $projects->fragment('recommendation-tab')->links() !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade px-3 py-4" id="rating" role="tabpanel" aria-labelledby="rating-tab">
                            <h3>Contractor's Reviews & Ratings</h3>
                            @forelse ($merchantratings as $rating)
                            <div class="d-md-flex reviews mb-4 text-center text-md-left">
                                <div class="review-profile-icon mb-4 w-100 mx-auto">
                                    {{ $rating->initials }}
                                </div>
                                <div class="w-100">
                                    <div class="ml-md-3 d-md-flex">
                                        <div>
                                            <h3 class="rating-header mb-3">{{ $rating->name }}</h3>
                                            <div class="mb-2">
                                                {!! $rating->pivot->ratingStars !!}
                                            </div>
                                        </div>
                                        <div class="ml-auto">
                                            <p class="paragraph txtgrey">{{ $rating->locationWithCityState }}</p>
                                        </div>
                                    </div>
                                    <div class="ml-md-3">
                                        <p class="paragraph txtgrey mb-0">
                                            {{ !empty($rating->pivot->review) ? $rating->pivot->review : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>No reviews found.</p>
                            @endforelse
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    {!! $merchantratings->fragment('rating-tab')->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="container services-card services-details">
                    <p class="services-title font-weight-bold txtblk mb-3">{{ $merchant->name }}</p>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user merchant-icon mr-4"></i>
                        <p class="mb-0">{{ $merchant->userDetail->pic_name }}</p>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-map-marker-alt merchant-icon mr-4"></i>
                        <p class="mb-0">{{ $merchant->projects->first()->location }}</p>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone merchant-icon mr-4"></i>
                        <p class="mb-0">{{ $merchant->formatted_phone_number }}</p>
                    </div>
                    <div class="row border-bottom my-4">
                        @if (!empty($merchant->userDetail->website))
                        <div class="col-4 text-center mb-3">
                            <a href="{{ $merchant->userDetail->website }}" title="{{ $merchant->userDetail->website }}" class="txtblk" target="_blank"><i class="fas fa-globe-asia" style="font-size: 30px;"></i></a>
                        </div>
                        @endif
                        @if (!empty($merchant->userDetail->facebook))
                        <div class="col-4 text-center mb-3">
                            <a href="{{ $merchant->userDetail->facebook }}" title="{{ $merchant->userDetail->facebook }}" class="txtblk" target="_blank"><i class="fab fa-facebook-square" style="font-size: 30px;"></i></a>
                        </div>
                        @endif
                        @if (!empty($merchant->userDetail->instagram))
                        <div class="col-4 text-center mb-3">
                            <a href="{{ $merchant->userDetail->instagram }}" title="{{ $merchant->userDetail->instagram }}" class="txtblk" target="_blank"><i class="fab fa-instagram" style="font-size: 30px;"></i></a>
                        </div>
                        @endif
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-10">
                            @if (!empty($merchant->userDetail->whatsapp))
                            <a class="btn btn-round green d-flex align-items-center justify-content-center shadow" role="button"
                                href="https://api.whatsapp.com/send?phone={{ $merchant->userDetail->whatsapp }}&text={{ urlencode(__('messages.whatsapp_message', ['name' => $merchant->name, 'link' => route('app.project.show', ['project' => $merchant->id])])) }}" class="btn" target="_blank">
                                <i class="fab fa-whatsapp services-icon text-white mr-2" aria-hidden="true" title="{{ __('app.project_details_btn_whatsapp') }}"></i>
                                {!! __('app.project_details_btn_whatsapp') !!}
                            </a>
                            @else
                            <a role="button" href="tel:{{ $merchant->formatted_phone_number }}" class="btn btn-round green d-flex align-items-center justify-content-center shadow" target="_blank">
                                <i class="fas fa-phone services-icon text-white mr-3" aria-hidden="true" title="{{ __('app.project_details_btn_call') }}"></i>
                                {!! __('app.project_details_btn_whatsapp') !!}
                            </a>
                            @endif
                            {{-- <a href="{{ route('app.merchant.show', ['merchant' => $merchant->id]) }}" class="btn btn-round d-flex align-items-center justify-content-center mt-4">
                                {{ __('app.project_btn_view_merchant') }}
                            </a>
                            --}}
                            @auth('web')
                            @member
                            @if(empty($ratings))
                            <button type="button" class="btn btn-round black d-flex align-items-center justify-content-center mt-4 w-100" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-star services-icon text-white mr-2"></i> {{ __('app.merchant_btn_rate_merchant') }}
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p class="mb-0">{{ __('app.merchant_rate_dropdown_title') }}</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('app.ratings.store') }}" method="POST" role="form" enctype="multipart/form-data" id="ratingform" data-merchant="{{ $merchant->id }}">
                                            <div class="modal-body">
                                                <div class="rate">
                                                    <input type="radio" id="star5" name="rate" value="5" />
                                                    <label for="star5"></label>
                                                    <input type="radio" id="star4" name="rate" value="4" />
                                                    <label for="star4"></label>
                                                    <input type="radio" id="star3" name="rate" value="3" />
                                                    <label for="star3"></label>
                                                    <input type="radio" id="star2" name="rate" value="2" />
                                                    <label for="star2"></label>
                                                    <input type="radio" id="star1" name="rate" value="1" />
                                                    <label for="star1"></label>
                                                </div>
                                                <textarea class="form-control" name="review" id="review" cols="30" rows="5" maxlength="255"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-black" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-orange btn-rate">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="my-3 text-center">
                                <p class="mb-2">Given rating</p>
                                {!! $ratings !!}
                            </div>
                            @endif
                            @endmember
                            @endauth

                            @guest
                            <a role="button" href="{{ route('login') }}" class="btn btn-round black d-flex align-items-center justify-content-center mt-4" aria-haspopup="true" aria-expanded="false">
                                {{ __('app.merchant_btn_login_rate') }}
                            </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div id="profile-2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2>{{ __('app.merchant_subtitle_service') }}:</h2>
            </div>
            <div class="col-12 mb-3">
                <p class="txtgrey">{{ __('app.merchant_service_title') }}</p>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <span class="ml-3">{{ $merchant->service->name }}</span>
                </div>
            </div>

        </div>
    </div>
</div> --}}

{{-- <div id="profile-3">
    <div class="container">
        <h4>{{ __('app.merchant_subtitle_projects', ['merchant' => $merchant->name]) }}</h4>
        <div class="row justify-content-start">

            @forelse ($projects as $project)
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="{{ route('app.project.show', ['project' => $project->id]) }}">
                        <div class="merchant-image-container">
                            <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->user->name }}" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">{{ $project->english_title }}</p> --}}
                            {{-- <p class="merchant-title">{{ $project->chinese_title }}</p> --}}
                            {{-- <p class="merchant-subtitle">{{ $project->user->name }}</p>
                            <p class="merchant-subtitle">
                                <span class="badge badge-pill badge-info badge-padding">{{ $project->user->service->name ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="merchant-footer"> --}}
                            {{-- <span class="merchant-footer-left">{{ __('app.price_from') . ' ' . $project->price_without_unit }}</span> --}}
                            {{-- <span class="merchant-footer-right"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location }}</span>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12 d-inline-flex">{{ __('messages.no_records') }}</div>
            @endforelse

        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {!! $projects->fragment('profile-3')->links() !!}
            </div>
        </div>
    </div>
</div> --}}

@endsection
@push('scripts')
<script>
    var arr = [];
    var hash = window.location.hash;

    $('#merchantprofile>li>a').each(function(){
        arr.push('#'+$(this).attr('id'));
    });

    if (hash != "" && $.inArray(hash, arr)) {
        $(hash).trigger('click');
        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 399);
    }else{
        $("#about-tab").trigger('click');
    }
</script>
@endpush