@extends('plugins/marketplace::themes.dashboard.master')

@section('content')
    <section class="ps-dashboard">
        <div class="ps-section__left">
            <div class="row">
                <div class="col-md-8">
                    <div class="ps-card ps-card--sale-report">
                        <div class="ps-card__header">
                            <h4>Sales Reports</h4>
                        </div>
                        <div class="ps-card__content">
                            <div id="chart"></div>
                        </div>
                        <div class="ps-card__footer">
                            <div class="row">
                                <div class="col-md-8">
                                    <p>Items Earning Sales ($)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ps-card ps-card--earning">
                        <div class="ps-card__header">
                            <h4>Earnings</h4>
                        </div>
                        <div class="ps-card__content">
                            <div class="ps-card__chart">
                                <div id="donut-chart"></div>
                                <div class="ps-card__information"><i class="icon icon-wallet"></i><strong>$12,560</strong><small>Balance</small></div>
                            </div>
                            <div class="ps-card__status">
                                <p class="yellow"><strong> $20,199</strong><span>Income</span></p>
                                <p class="red"><strong> $1,021</strong><span>Taxes</span></p>
                                <p class="green"><strong> $992.00</strong><span>Fees</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ps-card">
                <div class="ps-card__header">
                    <h4>Recent Orders</h4>
                </div>
                <div class="ps-card__content">
                    <div class="table-responsive">
                        <table class="table ps-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Payment</th>
                                <th>Fullfillment</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>#A580</td>
                                <td><strong> Aug, 15, 2020</strong></td>
                                <td><a href="order-detail.html"><strong>Unero Black Military</strong></a></td>
                                <td><span class="ps-badge success">Paid</span>
                                </td>
                                <td><span class="ps-fullfillment success">delivered</span>
                                </td>
                                <td><strong>$56.00</strong></td>
                                <td>
                                    <div class="dropdown"><a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item" href="#">Delete</a></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#B260</td>
                                <td><strong> Aug, 15, 2020</strong></td>
                                <td><a href="order-detail.html"><strong>Marsh Speaker</strong></a></td>
                                <td><span class="ps-badge gray">Unpaid</span>
                                </td>
                                <td><span class="ps-fullfillment success">delivered</span>
                                </td>
                                <td><strong>$56.00</strong></td>
                                <td>
                                    <div class="dropdown"><a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item" href="#">Delete</a></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#A583</td>
                                <td><strong> Aug, 15, 2020</strong></td>
                                <td><a href="order-detail.html"><strong>Lined Blend T-Shirt</strong></a></td>
                                <td><span class="ps-badge success">Paid</span>
                                </td>
                                <td><span class="ps-fullfillment warning">In Progress</span>
                                </td>
                                <td><strong>$516.00</strong></td>
                                <td>
                                    <div class="dropdown"><a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item" href="#">Delete</a></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#A583</td>
                                <td><strong> Aug, 15, 2020</strong></td>
                                <td><a href="order-detail.html"><strong>DJI MAcvic Quadcopter</strong></a></td>
                                <td><span class="ps-badge gray">Unpaid</span>
                                </td>
                                <td><span class="ps-fullfillment success">delivered</span>
                                </td>
                                <td><strong>$112.00</strong></td>
                                <td>
                                    <div class="dropdown"><a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item" href="#">Delete</a></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#A112</td>
                                <td><strong> Aug, 15, 2020</strong></td>
                                <td><a href="order-detail.html"><strong>Black T-Shirt</strong></a></td>
                                <td><span class="ps-badge success">Paid</span>
                                </td>
                                <td><span class="ps-fullfillment danger">Cancel</span>
                                </td>
                                <td><strong>$30.00</strong></td>
                                <td>
                                    <div class="dropdown"><a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item" href="#">Delete</a></div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="ps-card__footer"><a class="ps-card__morelink" href="orders.htmls">View Full Orders<i class="icon icon-chevron-right"></i></a></div>
            </div>
        </div>
        <div class="ps-section__right">
            <section class="ps-card ps-card--statics">
                <div class="ps-card__header">
                    <h4>Statics</h4>
                    <div class="ps-card__sortby"><i class="icon-calendar-empty"></i>
                        <div class="form-group--select">
                            <select class="form-control">
                                <option value="1">Last 30 days</option>
                                <option value="2">Last 90 days</option>
                                <option value="3">Last 180 days</option>
                            </select><i class="icon-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="ps-card__content">
                    <div class="ps-block--stat yellow">
                        <div class="ps-block__left"><span><i class="icon-cart"></i></span></div>
                        <div class="ps-block__content">
                            <p>Orders</p>
                            <h4>254<small class="asc"><i class="icon-arrow-up"></i><span>12,5%</span></small></h4>
                        </div>
                    </div>
                    <div class="ps-block--stat pink">
                        <div class="ps-block__left"><span><i class="icon-cart"></i></span></div>
                        <div class="ps-block__content">
                            <p>Revenue</p>
                            <h4>$6,260<small class="asc"><i class="icon-arrow-up"></i><span>7.1%</span></small></h4>
                        </div>
                    </div>
                    <div class="ps-block--stat green">
                        <div class="ps-block__left"><span><i class="icon-cart"></i></span></div>
                        <div class="ps-block__content">
                            <p>Revenue</p>
                            <h4>$2,567<small class="desc"><i class="icon-arrow-down"></i><span>0.32%</span></small></h4>
                        </div>
                    </div>
                </div>
            </section>
            <section class="ps-card ps-card--top-country">
                <div class="ps-card__header">
                    <h4>Top Countries</h4>
                </div>
                <div class="ps-card__content">
                    <div class="row">
                        <div class="col-6">
                            <figure class="organge">
                                <figcaption>United States</figcaption><strong>80%</strong>
                            </figure>
                        </div>
                        <div class="col-6">
                            <figure class="red">
                                <figcaption>United Kingdom</figcaption><strong>65%</strong>
                            </figure>
                        </div>
                        <div class="col-6">
                            <figure class="green">
                                <figcaption>Germany</figcaption><strong>65%</strong>
                            </figure>
                        </div>
                        <div class="col-6">
                            <figure class="cyan">
                                <figcaption>Russia</figcaption><strong>35%</strong>
                            </figure>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
@stop
