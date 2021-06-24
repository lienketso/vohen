<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Assets;
use Botble\Marketplace\Tables\OrderTable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Response;
use Throwable;

class OrderController
{
    /**
     * DashboardController constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        Assets::setConfig($config->get('plugins.marketplace.assets', []));
    }

    /**
     * @param Request $request
     * @param OrderTable $table
     * @return JsonResponse|View|Response
     * @throws Throwable
     */
    public function index(OrderTable $table)
    {
        page_title()->setTitle(__('Orders'));

        return $table->render('plugins/marketplace::themes.dashboard.table.base');
    }
}
