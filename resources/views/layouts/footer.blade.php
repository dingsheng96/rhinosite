@if (Auth::check() && (!isset($guest_view) || !$guest_view))

<footer class="main-footer d-flex flex-md-row flex-column">

    <div class="px-md-2">
        <a href="{{ route('app.term') }}" target="_blank">
            {{ __('labels.terms_policy') }}
        </a>
    </div>

    <div class="px-md-2">
        <a href="{{ route('app.privacy') }}" target="_blank">
            {{ __('labels.privacy_policy') }}
        </a>
    </div>

    <div class="px-md-2 ml-md-auto">
        {!! __('labels.copyright') !!}
    </div>
</footer>

@else

<div id="mobilefooter" class="navbar navbar-expand-lg navbar-light">
    <div class="container px-4 py-3">
        <a class="navbar-brand" href="{{ route('app.home') }}"><img src="{{ asset('storage/logo-footer.png') }}" alt="rhinosite_logo" class="footer-logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#footercollapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="footercollapse">
        <div class="footer-background">
            <div class="container">
                <div class="footer-content-container">
                    <div class="footer-left-content">
                        <p class="mb-5">{{ __('app.tagline') }}</p>
                        <p>{{ __('app.social_media') }}</p>
                        <div class="d-flex">
                            <img src="{{ asset('storage/facebook.png') }}" alt="facebook_icon" class="footer-social">
                            <img src="{{ asset('storage/instagram.png') }}" alt="instagram_icon" class="footer-social">
                        </div>
                    </div>
                    <div class="footer-right">

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.about') }}</li>
                                <li><a href="{{ route('app.about') }}">{{ __('app.our_story') }}</a></li>
                                <li><a href="{{ route('app.contact') }}">{{ __('app.contact_us') }}</a></li>
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

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.contractor') }}</li>
                                <li><a href="{{ route('app.partner') }}">{{ __('app.join_now') }}</a></li>
                            </ul>
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
            <div class="container">
                <div class="footer-content-container">
                    <div class="footer-left-content">
                        <img src="{{ asset('storage/logo-footer.png') }}" alt="rhinosite-footer_logo" class="footer-logo mb-5">
                        <p class="mb-5">{{ __('app.tagline') }}</p>
                        <p>{{ __('app.social_media') }}</p>
                        <div class="d-flex">
                            <img src="{{ asset('storage/facebook.png') }}" alt="facebook_icon" class="footer-social">
                            <img src="{{ asset('storage/instagram.png') }}" alt="instagram_icon" class="footer-social">
                        </div>
                    </div>
                    <div class="footer-right">

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.about') }}</li>
                                <li><a href="{{ route('app.about') }}">{{ __('app.our_story') }}</a></li>
                                <li><a href="{{ route('app.contact') }}">{{ __('app.contact_us') }}</a></li>
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

                        <div class="footer-right-content">
                            <ul>
                                <li class="header">{{ __('app.contractor') }}</li>
                                <li><a href="{{ route('app.partner') }}">{{ __('app.join_now') }}</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer-bottom">
    <div class="container">
        <div class="d-flex">
            <ul>
                <li><a href="{{ route('app.term') }}">{{ __('labels.terms_policy') }}</a></li>
                <li><a href="{{ route('app.privacy') }}">{{ __('labels.privacy_policy') }}</a></li>
            </ul>
            <ul class="ml-auto">
                <li class="ml-auto">{!! __('labels.copyright') !!}</li>
            </ul>
        </div>
    </div>
</div>

@endif