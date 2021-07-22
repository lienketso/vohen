@extends('plugins/marketplace::themes.dashboard.master')

@section('content')

    <div class="container page-content" style="padding: 20px">
        <div class="header_content">
            <h3>Công cụ marketing </h3>
            <p>Tăng doanh số bằng công cụ marketing</p>
        </div>

        <div class="list-marketing">
            <div class="row">

                <div class="col-lg-4">
                    <div class="marketing-item">
                        <div class="icon-marketing">
                            <a href="{{route('marketplace.vendor.discounts.index')}}"><img src="{{ asset('vendor/core/plugins/marketplace/img/2103301422-voucher.png') }}"></a>
                        </div>
                        <div class="desc-marketing">
                            <h4><a href="{{route('marketplace.vendor.discounts.index')}}">Mã giảm giá của tôi</a></h4>
                            <p>Công cụ tăng đơn hàng bằng cách tạo mã giảm giá tặng cho người mua</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="marketing-item">
                        <div class="icon-marketing">
                            <a href="{{route('marketplace.vendor.discounts.index')}}"><img src="{{ asset('vendor/core/plugins/marketplace/img/sale.jpg') }}"></a>
                        </div>
                        <div class="desc-marketing">
                            <h4><a href="#">Chương trình của tôi</a></h4>
                            <p>Công cụ tăng đơn hàng bằng cách giảm giá trên mỗi sản phẩm</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="marketing-item">
                        <div class="icon-marketing">
                            <a href="{{route('marketplace.vendor.discounts.index')}}"><img src="{{ asset('vendor/core/plugins/marketplace/img/deal.png') }}"></a>
                        </div>
                        <div class="desc-marketing">
                            <h4><a href="#">Mua kèm deal giá sốc</a></h4>
                            <p>Công cụ tăng đơn hàng bằng cách tạo tạo các deal sốc</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @endsection