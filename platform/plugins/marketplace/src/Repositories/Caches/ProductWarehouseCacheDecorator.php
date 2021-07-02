<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 02/07/2021
 * Time: 3:31 CH
 */

namespace Botble\Marketplace\Repositories\Caches;
use Botble\Marketplace\Repositories\Interfaces\ProductWarehouseInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductWarehouseCacheDecorator extends CacheAbstractDecorator implements ProductWarehouseInterface
{

}