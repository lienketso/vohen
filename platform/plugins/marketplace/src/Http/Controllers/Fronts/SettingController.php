<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Assets;
use Illuminate\Contracts\Config\Repository;

class SettingController
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
        page_title()->setTitle(__('Settings'));

        return view('plugins/marketplace::themes.dashboard.settings');
    }
}
