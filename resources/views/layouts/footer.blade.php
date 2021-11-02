<div id="mobilefooter" class="navbar navbar-expand-lg navbar-light">
    <div class="container px-4 py-3">
        <a class="navbar-brand" href="{{ route('app.home') }}"><img src="{{ asset('assets/logo-footer.png') }}" alt="rhinosite_logo" class="footer-logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#footercollapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="footercollapse">
        <div class="footer-background">
            <div class="container-fluid px-0 d-block">
                <div class="footer-content-container">
                    <div class="footer-left-content p-4">
                        <p class="mb-5">{{ __('app.home_title_main') }}</p>
                        <p>{{ __('app.social_media') }}</p>
                        <div class="d-flex">
                            <a href="https://www.facebook.com/rhinositemy">
                                <img src="{{ asset('assets/facebook.png') }}" alt="facebook_icon" class="footer-social">
                            </a>
                            <a href="https://www.instagram.com/rhinosite_my/">
                                <img src="{{ asset('assets/instagram.png') }}" alt="instagram_icon" class="footer-social">
                            </a>
                        </div>
                    </div>
                    <div class="footer-middle p-4">
                        <ul class="mb-0">
                            <li class="header">{!! __('app.find_all_local_contractor') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_1') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_2') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_3') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_4') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_5') !!}</li>
                            <li class="pb-0">{!! __('app.find_all_local_contractor_list_6') !!}</li>
                        </ul>
                    </div>
                    <div class="footer-right p-4">

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.about') }}</li>
                                <li><a href="{{ route('app.about') }}">{{ __('modules.app.about') }}</a></li>
                                <li><a href="{{ route('app.project.index') }}">{{ __('modules.app.merchant') }}</a></li>
                                <li><a href="{{ route('app.management') }}">{{ __('modules.app.management') }}</a></li>
                                <li><a href="{{ route('app.contact') }}">{{ __('app.contact_us') }}</a></li>
                            </ul>
                        </div>

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.contractor') }}</li>
                                <li><a href="{{ route('app.partner') }}">{{ __('app.join_now') }}</a></li>
                            </ul>
                        </div>

                        <div class="footer-right-content">
                            @if (isset($services))
                            <ul>
                                <li class="header">{{ __('app.top_services') }}</li>
                                @forelse ($services->take(6) as $service)
                                <li><a href="{{ route('app.project.index', ['q' => $service->name]) }}">{{ $service->name }}</a></li>
                                @empty
                                @endforelse
                            </ul>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer">
    <div class="container-fluid px-0 d-flex">
        <div class="footer-background">
            <div class="container-fluid container-lg">
                <div class="row footer-content-container">
                    <div class="col-lg-3 footer-left-content p-5">
                        <img src="{{ asset('assets/logo-footer.png') }}" alt="rhinosite-footer_logo" class="footer-logo mb-5">

                        <p class="mb-5">{{ __('app.home_title_main') }}</p>
                        <p>{{ __('app.social_media') }}</p>
                        <div class="d-flex">
                            <a href="https://www.facebook.com/rhinositemy" target="blank">
                                <img src="{{ asset('assets/facebook.png') }}" alt="facebook_icon" class="footer-social">
                            </a>
                            <a href="https://www.instagram.com/rhinosite_my/" target="blank">
                                <img src="{{ asset('assets/instagram.png') }}" alt="instagram_icon" class="footer-social">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 footer-middle p-5">
                        <ul class="mb-0">
                            <li class="header">{!! __('app.find_all_local_contractor') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_1') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_2') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_3') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_4') !!}</li>
                            <li>{!! __('app.find_all_local_contractor_list_5') !!}</li>
                            <li class="pb-0">{!! __('app.find_all_local_contractor_list_6') !!}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4 footer-right p-5">

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.about') }}</li>
                                <li><a href="{{ route('app.about') }}">{{ __('modules.app.about') }}</a></li>
                                <li><a href="{{ route('app.project.index') }}">{{ __('modules.app.merchant') }}</a></li>
                                <li><a href="{{ route('app.management') }}">{{ __('modules.app.management') }}</a></li>
                                <li><a href="{{ route('app.contact') }}">{{ __('app.contact_us') }}</a></li>
                            </ul>
                            <ul>
                                <li class="header">{{ __('app.contractor') }}</li>
                                <li><a href="{{ route('app.partner') }}">{{ __('app.join_now') }}</a></li>
                            </ul>
                        </div>

                        <div class="footer-right-content">
                            @if (isset($services))
                            <ul>
                                <li class="header">{{ __('app.top_services') }}</li>
                                @forelse ($services->take(6) as $service)
                                <li><a href="{{ route('app.project.index', ['q' => $service->name]) }}">{{ $service->name }}</a></li>
                                @empty
                                @endforelse
                            </ul>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer-bottom">
    <div class="container">
        <div class="d-block d-sm-flex text-center text-sm-left">
            <ul class="d-block d-sm-inline-block">
                <li><a href="{{ route('app.term') }}">{{ __('labels.terms_policy') }}</a></li>
                <li><a href="{{ route('app.privacy') }}">{{ __('labels.privacy_policy') }}</a></li>
            </ul>
            <ul class="ml-auto d-block d-sm-inline-block">
                <li class="ml-auto">{!! __('labels.copyright') !!}</li>
            </ul>
        </div>
    </div>
</div>