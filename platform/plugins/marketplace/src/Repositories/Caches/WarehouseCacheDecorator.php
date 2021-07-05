<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 30/06/2021
 * Time: 1:50 CH
 */

namespace Botble\Marketplace\Repositories\Caches;


use Botble\Marketplace\Repositories\Interfaces\WarehouseInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class WarehouseCacheDecorator extends CacheAbstractDecorator implements WarehouseInterface
{

}