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
    });
});
