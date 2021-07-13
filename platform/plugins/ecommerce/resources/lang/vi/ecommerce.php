<?php

return [
    'settings'                        => 'Cài đặt',
    'name'                            => 'Thương mại điện tử',
    'setting'                         => [
        'email' => [
            'title'                                   => 'E-commerce',
            'description'                             => 'Ecommerce email config',
            'order_confirm_subject'                   => 'Subject of order confirmation email',
            'order_confirm_subject_placeholder'       => 'The subject of email confirmation send to the customer',
            'order_confirm_content'                   => 'Content of order confirmation email',
            'order_status_change_subject'             => 'Subject of email when changing order\'s status',
            'order_status_change_subject_placeholder' => 'Subject of email when changing order\'s status send to customer',
            'order_status_change_content'             => 'Content of email when changing order\'s status',
            'from_email'                              => 'Email from',
            'from_email_placeholder'                  => 'Email from address to display in mail. Ex: example@gmail.com',
        ],
        'title'                                 => 'Thông tin cơ bản',
        'state'                                 => 'Bang/Quận',
        'city'                                  => 'Thành phố',
        'country'                               => 'Quốc gia',
        'select country'                        => 'Chọn quố gia...',
        'weight_unit_gram'                      => 'Gram (g)',
        'weight_unit_kilogram'                  => 'Kilogram (kg)',
        'height_unit_cm'                        => 'Centimeter (cm)',
        'height_unit_m'                         => 'Meter (m)',
        'store_locator_title'                   => 'Địa chỉ cửa hàng',
        'store_locator_description'             => 'Tất cả các địa chỉ và chi nhánh,... của bạn. Địa chỉ sẽ dùng để theo vết, bán hàng và cấu hình thuế (nếu có)',
        'phone'                                 => 'Điện thoại',
        'address'                               => 'Địa chỉ',
        'is_primary'                            => 'Đặt làm mặc định?',
        'add_new'                               => 'Thêm mới',
        'or'                                    => 'hoặc',
        'change_primary_store'                  => 'thay địa chỉ mặc định',
        'other_settings'                        => 'Other settings',
        'other_settings_description'            => 'Settings for cart, review...',
        'enable_cart'                           => 'Enable shopping cart?',
        'enable_tax'                            => 'Enable tax?',
        'display_product_price_including_taxes' => 'Display product price including taxes?',
        'enable_review'                         => 'Enable review?',
        'enable_quick_buy_button'               => 'Enable quick buy button?',
        'quick_buy_target'                      => 'Quick buy target page?',
        'checkout_page'                         => 'Checkout page',
        'cart_page'                             => 'Cart page',
        'add_location'                          => 'Add location',
        'edit_location'                         => 'Edit location',
        'delete_location'                       => 'Delete location',
        'change_primary_location'               => 'Change primary location',
        'delete_location_confirmation'          => 'Are you sure you want to delete this location? This action cannot be undo.',
        'save_location'                         => 'Save location',
        'accept'                                => 'Accept',
        'select_country'                        => 'Select country',
        'zip_code_enabled'                      => 'Enable Zip Code?',
        'thousands_separator'                   => 'Dấu ngăn phần nghìn',
        'decimal_separator'                     => 'Dấu ngăn thập phân',
        'separator_period'                      => 'Dấu chấm (.)',
        'separator_comma'                       => 'Dấu phẩy (,)',
        'separator_space'                       => 'Khoảng trống ( )',
        'available_countries'                   => 'Available countries',
        'all'                                   => 'All',
        'verify_customer_email'                 => 'Verify customer\' email?',
    ],
    'store_address'                   => 'Địa chỉ cửa hàng',
    'store_phone'                     => 'Số điện thoại cửa hàng',
    'order_id'                        => 'Mã đơn hàng',
    'order_token'                     => 'Chuỗi mã hoá đơn hàng',
    'customer_name'                   => 'Tên khách hàng',
    'customer_email'                  => 'Email khách hàng',
    'customer_phone'                  => 'Số điện thoại khách hàng',
    'customer_address'                => 'Địa chỉ khách hàng',
    'product_list'                    => 'Danh sách sản phẩm trong đơn hàng',
    'payment_detail'                  => 'Chi tiết thanh toán',
    'shipping_method'                 => 'Phương thức vận chuyển',
    'payment_method'                  => 'Phương thức thanh toán',
    'standard_and_format'             => 'Tiêu chuẩn & Định dạng',
    'standard_and_format_description' => 'Các tiêu chuẩn và các định dạng được sử dụng để tính toán những thứ như giá cả sản phẩm, trọng lượng vận chuyển và thời gian đơn hàng được đặt.',
    'change_order_format'             => 'Chỉnh sửa định dạng mã đơn hàng (tùy chọn)',
    'change_order_format_description' => 'Mã đơn hàng mặc định bắt đầu từ :number. Bạn có thể thay đổi chuỗi bắt đầu hoặc kết thúc để tạo mã đơn hàng theo ý bạn, ví dụ "DH-:number" hoặc ":number-A"',
    'start_with'                      => 'Bắt đầu bằng',
    'end_with'                        => 'Kết thúc bằng',
    'order_will_be_shown'             => 'Mã đơn hàng của bạn sẽ hiển thị theo mẫu',
    'weight_unit'                     => 'Đơn vị cân nặng',
    'height_unit'                     => 'Đơn vị chiều dài/chiều cao',
    'import-data'                     => 'Đồng bộ dữ liệu'
];
