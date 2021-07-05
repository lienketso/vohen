<?php

use Botble\Marketplace\Models\Store;

Route::group(['namespace' => 'Botble\Marketplace\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get(SlugHelper::getPrefix(Store::class, 'stores'), [
            'as'   => 'public.stores',
            'uses' => 'PublicStoreController@getStores',
        ]);

        Route::get(SlugHelper::getPrefix(Store::class, 'stores') . '/{slug}', [
            'uses' => 'PublicStoreController@getStore',
        ]);
    });

    Route::group(['prefix' => 'vendor', 'as' => 'marketplace.vendor.', 'middleware' => ['vendor']], function () {

        Route::group(['prefix' => 'ajax'], function () {
            Route::post('upload', [
                'as'   => 'upload',
                'uses' => 'DashboardController@upload',
            ]);
        });

        Route::get('dashboard', [
            'as'   => 'dashboard',
            'uses' => 'DashboardController@index',
        ]);

        Route::get('orders', [
            'as'   => 'orders',
            'uses' => 'OrderController@index',
        ]);

        Route::get('products', [
            'as'   => 'products',
            'uses' => 'ProductController@index',
        ]);

        Route::get('settings', [
            'as'   => 'settings',
            'uses' => 'SettingController@index',
        ]);

        Route::resource('revenues', 'RevenueController')
            ->parameters(['' => 'revenue'])
            ->only(['index']);

        Route::resource('withdrawals', 'WithdrawalController')
            ->parameters(['' => 'withdrawal'])
            ->only([
                'index',
                'create',
                'store',
                'edit',
                'update'
            ]);

        Route::group(['prefix' => 'withdrawals'], function () {
            Route::get('show/{id}', [
                'as'   => 'withdrawals.show',
                'uses' => 'WithdrawalController@show',
            ]);
        });

        // quản lý kho
        Route::get('warehouse', [
            'as'   => 'warehouse',
            'uses' => 'WarehouseController@index',
        ]);
        Route::get('warehouse-create', [
            'as'   => 'warehouse.create',
            'uses' => 'WarehouseController@create',
        ]);
        Route::post('warehouse-create', [
            'as'   => 'warehouse.create',
            'uses' => 'WarehouseController@store',
        ]);
        Route::get('warehouse-edit/{id}', [
            'as'   => 'warehouse.edit',
            'uses' => 'WarehouseController@edit',
        ]);
        Route::post('warehouse-edit/{id}', [
            'as'   => 'warehouse.edit',
            'uses' => 'WarehouseController@update',
        ]);
        Route::get('warehouse-delete/{id}', [
            'as'   => 'warehouse.delete',
            'uses' => 'WarehouseController@destroy',
        ]);
        Route::get('warehouse-import/{id}', [
            'as'   => 'warehouse.import',
            'uses' => 'WarehouseController@import',
        ]);

    });

    Route::group(['prefix' => 'vendor', 'as' => 'marketplace.vendor.', 'middleware' => ['customer']], function () {

        Route::get('become-vendor', [
            'as'   => 'become-vendor',
            'uses' => 'DashboardController@getBecomeVendor',
        ]);

        Route::post('become-vendor', [
            'as'   => 'become-vendor',
            'uses' => 'DashboardController@postBecomeVendor',
        ]);

    });

});
