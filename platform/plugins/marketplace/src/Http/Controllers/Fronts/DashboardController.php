<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Assets;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use RvMedia;

class DashboardController
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        page_title()->setTitle(__('Dashboard'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/marketplace/plugins/apexcharts-bundle/dist/apexcharts.min.js',
            'vendor/core/plugins/marketplace/js/chart.js',
        ])
            ->addStylesDirectly('vendor/core/plugins/marketplace/plugins/apexcharts-bundle/dist/apexcharts.css');

        return view('plugins/marketplace::themes.dashboard.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function upload(Request $request)
    {
        return RvMedia::uploadFromEditor($request);
    }
}
