{!! Theme::partial('header') !!}

<div id="homepage-1">
    {!! Theme::content() !!}



    <section class="product_recoment mb-60">
        <div class="ps-container">
            <h3 class="title_product_cat">Sản phẩm gợi ý</h3>
            <div class="list_recoment_product">
                <div class="row">
                    @for($i=1;$i<=12;$i++)
                        <div class="col-lg-2 col-6">
                            <div class="ps-product">
                                <div class="ps-product__thumbnail">
                                    <a href="http://vohen.test/products/beat-headphone">
                                        <img src="https://image.voso.vn/users/vosoimage/images/decd94b9b08cc63c622066c56107101c?t%5B0%5D=maxSize%3Awidth%3D256%2Cheight%3D256&t%5B1%5D=compress%3Alevel%3D100&accessToken=1f71ebd389918055b2bccd5f2abd5e8df0853891ac0e1282d88a996efe170ba9" alt="Beat Headphone">
                                    </a>
                                    <ul class="ps-product__actions">
                                        <li><a class="add-to-cart-button" data-id="3" href="http://vohen.test/cart/add-to-cart" title="Thêm vào giỏ hàng"><i class="icon-bag2"></i></a></li>
                                        <li><a href="http://vohen.test/ajax/quick-view/3" title="Quick View" class="js-quick-view-button"><i class="icon-eye"></i></a></li>
                                        <li><a class="js-add-to-wishlist-button" href="http://vohen.test/wishlist/3" title="Add to Wishlist"><i class="icon-heart"></i></a></li>
                                        <li><a class="js-add-to-compare-button" href="http://vohen.test/compare/3" title="Compare"><i class="icon-chart-bars"></i></a></li>
                                    </ul>
                                </div>
                                <div class="ps-product__container">
                                    <div class="ps-product__content">
                                        <a class="ps-product__title" href="http://vohen.test/products/beat-headphone">Bình thủy tinh kim cương</a>
                                        <div class="rating_wrap">
                                            <div class="rating">
                                                <div class="product_rate" style="width: 40%"></div>
                                            </div>
                                            <span class="rating_num">(1)</span>
                                        </div>
                                        <p class="ps-product__price ">20.000 ₫ </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>


            </div>
        </div>
    </section>

</div>

{!! Theme::partial('footer') !!}
