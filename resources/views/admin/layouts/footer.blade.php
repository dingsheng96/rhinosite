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