<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 05/07/2021
 * Time: 3:10 CH
 */

namespace Botble\Marketplace\Repositories\Caches;
use Botble\Marketplace\Repositories\Interfaces\WarehouseImportInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class WarehouseImportCacheDecorator extends CacheAbstractDecorator implements WarehouseImportInterface
{

}