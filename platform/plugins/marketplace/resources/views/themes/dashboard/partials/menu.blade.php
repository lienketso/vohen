@php
    $menus = [
        [
            'key'   => 'marketplace.vendor.dashboard',
            'icon'  => 'icon-home',
            'name'  => __('Dashboard')
        ],
        [
            'key'   => 'marketplace.vendor.store',
            'icon'  => 'icon-cart',
            'name'  => 'Gian hàng'
        ],
        [
            'key'   => 'marketplace.vendor.products.index',
            'icon'  => 'icon-database',
            'name'  => __('Products')
        ],
        [
            'key'   => 'marketplace.vendor.orders.index',
            'icon'  => 'icon-bag2',
            'name'  => __('Orders')
        ],
        [
            'key'   => 'marketplace.vendor.withdrawals.index',
            'icon'  => 'icon-bag-dollar',
            'name'  => 'Rút tiền'
        ],
        [
            'key'   => 'marketplace.vendor.warehouse',
            'icon'  => 'icon-accessibility',
            'name'  => 'Quản lý kho'
        ],
        [
            'key'   => 'marketplace.vendor.settings',
            'icon'  => 'icon-cog',
            'name'  => 'Cấu hình'
        ],
    ];
@endphp

<ul class="menu">
    @foreach ($menus as $item)
        <li>
            <a @if (Route::currentRouteName() == $item['key']) class="active" @endif href="{{ route($item['key']) }}">
                <i class="{{ $item['icon'] }}"></i>{{ $item['name'] }}
            </a>
        </li>
    @endforeach
    <li><a href="{{ route('customer.overview') }}"><i class="icon-user"></i>{{ __('Customer dashboard') }}</a></li>
</ul>
