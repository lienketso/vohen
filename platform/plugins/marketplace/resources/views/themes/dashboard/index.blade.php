@extends('plugins/marketplace::themes.dashboard.master')

@section('content')
@php
    $totalProducts = $store->products()->count();
    $totalOrders = $store->orders()->count();
@endphp
    <section class="ps-dashboard">
        <div class="ps-section__left">
            <div class="row">
                @if (!$totalProducts)
                    <div class="col-12">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </symbol>
                        </svg>
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#check-circle-fill"/></svg>
                                {{ __('Congratulations on being a vendor at :site_title', ['site_title' => theme_option('site_title')]) }}
                            </h4>
                            <p>{{ __('Attract your customers with the best products.') }}</p>
                            <hr>
                            <p class="mb-0">{!! __('Create a new product <a href=":url">here</a>', ['url' => route('marketplace.vendor.products.create')]) !!}</p>
                        </div>
                    </div>
                @elseif (!$totalOrders)
                    <div class="col-12">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                              </symbol>
                        </svg>
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                                {{ __('You have :total product(s) but no orders yet', ['total' => $totalProducts]) }}
                            </h4>
                            <hr>
                            <p class="mb-0">{!! __('View your store <a href=":url">here</a>', ['url' => $user->store->url]) !!}</p>
                        </div>
                    </div>
                @else
                    <div class="col-md-8">
                        <div class="ps-card ps-card--sale-report">
                            <div class="ps-card__header">
                                <h4>{{ __('Sales Reports') }}</h4>
                                <a href="{{ route('marketplace.vendor.revenues.index') }}"><small>{{ __('Revenues') }} <i class="fas fa-angle-double-right"></i></small></a>
                            </div>
                            <div class="ps-card__content">
                                <order-in-month-chart url="{{ route('marketplace.vendor.chart.month') }}" ></order-in-month-chart>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ps-card ps-card--earning">
                            <div class="ps-card__header">
                                <h4>{{ __('Earnings') }}</h4>
                            </div>
                            <div class="ps-card__content">
                                <div class="ps-card__chart">
                                    <revenue-chart :data="{{ json_encode([['label' => __('Revenue'), 'value' => $user->total_revenue], ['label' => __('Fees'), 'value' => $user->total_fee]]) }}"></revenue-chart>
                                    <div class="ps-card__information">
                                        <i class="icon icon-wallet"></i>
                                        <strong>{{ format_price($user->balance) }}</strong>
                                        <small>{{ __('Balance') }}</small>
                                    </div>
                                </div>
                                <div class="ps-card__status">
                                    <p class="yellow"><strong> {{ format_price($user->total_revenue) }}</strong><span>{{ __('Revenue') }}</span></p>
                                    <p class="green"><strong> {{ format_price($user->total_fee) }}</strong><span>{{ __('Fees') }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if ($totalOrders)
                <div class="ps-card">
                    <div class="ps-card__header">
                        <h4>{{ __('Recent Orders') }}</h4>
                    </div>
                    <div class="ps-card__content">
                        <div class="table-responsive">
                            <table class="table ps-table">
                                <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if (count($orders) > 0 && $orders->loadMissing(['user', 'payment']))
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ get_order_code($order->id) }}</td>
                                                <td><strong>{{ $order->created_at->format('M d, Y') }}</strong></td>
                                                <td><a href="{{ route('marketplace.vendor.orders.edit', $order->id) }}"><strong>{{ $order->user->name ?: $order->address->name }}</strong></a></td>
                                                <td>{!! $order->payment->status->toHtml() !!}</td>
                                                <td>{!! $order->status->toHtml() !!}</td>
                                                <td><strong>{{ format_price($order->amount) }}</strong></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('No orders!') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ps-card__footer"><a class="ps-card__morelink" href="{{ route('marketplace.vendor.orders.index') }}">{{ __('View Full Orders') }}<i class="icon icon-chevron-right"></i></a></div>
                </div>
            @endif
        </div>
        <div class="ps-section__right">
            <section class="ps-card ps-card--statics">
                <div class="ps-card__header">
                    <h4>{{ __('Statistics') }}</h4>
<!--                    <div class="ps-card__sortby"><i class="icon-calendar-empty"></i>
                        <div class="form-group&#45;&#45;select">
                            <select class="form-control">
                                <option value="1">Last 30 days</option>
                                <option value="2">Last 90 days</option>
                                <option value="3">Last 180 days</option>
                            </select><i class="icon-chevron-down"></i>
                        </div>
                    </div>-->
                </div>
                <div class="ps-card__content">
                    <div class="ps-block--stat yellow">
                        <div class="ps-block__left"><span><i class="icon-bag2"></i></span></div>
                        <div class="ps-block__content">
                            <p>{{ __('Orders') }}</p>
                            <h4>{{ $totalOrders }}<small class="asc"><i class="icon-arrow-up"></i></small></h4>
                        </div>
                    </div>
                    <div class="ps-block--stat pink">
                        <div class="ps-block__left"><span><i class="icon-bag-dollar"></i></span></div>
                        <div class="ps-block__content">
                            <p>{{ __('Revenue') }}</p>
                            <h4>{{ format_price($user->total_revenue) }}<small class="asc"><i class="icon-arrow-up"></i></small></h4>
                        </div>
                    </div>
                    <div class="ps-block--stat green">
                        <div class="ps-block__left"><span><i class="icon-database"></i></span></div>
                        <div class="ps-block__content">
                            <p>{{ __('Products') }}</p>
                            <h4>{{ $totalProducts }}<small class="desc"><i class="icon-arrow-down"></i></small></h4>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
@stop
