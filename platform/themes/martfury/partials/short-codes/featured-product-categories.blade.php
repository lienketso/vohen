<div class="ps-top-categories mt-40 mb-40">

    <div class="container bg-white">
        <div class="bg_category_home ">
        <h3>{!! clean($title) !!}</h3>
        <featured-product-categories-component url="{{ route('public.ajax.featured-product-categories') }}"></featured-product-categories-component>
        </div>
    </div>
</div>
