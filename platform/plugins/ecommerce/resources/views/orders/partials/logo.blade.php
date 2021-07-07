@if (theme_option('logo'))
    <div class="checkout-logo">
        <div class="container">
            <a href="{{ route('public.index') }}" title="{{ theme_option('site_title') }}">
                <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" class="img-fluid" width="150" alt="{{ theme_option('site_title') }}" />
            </a>
        </div>
    </div>
    <hr>
@endif
