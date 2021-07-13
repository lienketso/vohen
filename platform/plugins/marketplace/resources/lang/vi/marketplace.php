<?php

return [
    'name'            => 'Marketplace',
    'email'           => [
        'store_name'                  => 'Tên cửa hàng',
        'store_new_order_title'       => 'Gửi đơn đặt hàng đến cửa hàng',
        'store_new_order_description' => 'Gửi email đến cửa hàng khi khách hàng đặt hàng',
    ],
    'current_balance'   => 'Số dư hiện tại',
    'settings'          => [
        'name'              => 'Cấu hình',
        'title'             => 'Cấu hình cho marketplace',
        'description'       => '...',
        'fee_per_order'     => 'Phí mỗi đơn hàng (%), đề xuất: 2 hoặc 3',
        'fee_withdrawal'    => 'Phí rút tiền (Số tiền cố định)',
        'check_valid_signature' => 'Kiểm tra chữ ký hợp lệ trong thu nhập của nhà cung cấp',
    ],
];
