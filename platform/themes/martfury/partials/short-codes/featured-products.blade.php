<div class="ps-product-list mt-40 mb-40">
    <div class="ps-container">
        <div class="bg-flash-sale bg-white">
        <div class="ps-section__header">
            <h3>Flash Sale</h3>
            <ul class="ps-section__links">
                <li><a href="{{ route('public.products') }}">{{ __('View All') }}</a></li>
            </ul>
        </div>
        <featured-products-component url="{{ route('public.ajax.featured-products') }}"></featured-products-component>
        </div>
    </div>
</div>

