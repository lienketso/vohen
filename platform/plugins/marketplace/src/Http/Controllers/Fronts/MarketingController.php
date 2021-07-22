<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 22/07/2021
 * Time: 2:37 CH
 */

namespace Botble\Marketplace\Http\Controllers\Fronts;


use Botble\Base\Http\Controllers\BaseController;

class MarketingController extends BaseController
{
    public function index(){
        page_title()->setTitle('Marketing');
        return view('plugins/marketplace::themes.dashboard.marketing.index');
    }
}