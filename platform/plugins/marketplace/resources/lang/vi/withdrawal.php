<?php

return [
    'name'     => 'Yêu cầu rút tiền',
    'edit'     => 'Sửa yêu cầu rút tiền',
    'statuses' => [
        'pending'   => 'Đang chờ xử lý',
        'processing'=> 'Đang xử lý',
        'completed' => 'Đã hoàn thành',
        'canceled'  => 'Đã hủy',
        'refused'   => 'Từ chối',
    ],
    'amount'   => 'Số tiền',
    'customer' => 'Khách hàng',
    'vendor'   => 'Người bán hàng',
    'currency' => 'Tiền tệ',
    'forms' => [
        'amount'                => 'Số tiền',
        'amount_placeholder'    => 'Số tiền mà bạn muốn rút',
        'fee'                   => 'Phí',
        'fee_helper'            => 'Bạn phải trả phí khi rút tiền: :fee',
    ]
];
