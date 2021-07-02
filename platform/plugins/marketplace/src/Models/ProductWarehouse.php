<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 02/07/2021
 * Time: 12:10 CH
 */

namespace Botble\Marketplace\Models;


use Botble\Base\Models\BaseModel;

class ProductWarehouse extends BaseModel
{
    protected $table = 'ec_product_warehouse';
    protected $fillable = ['warehouse_id','product_id','qty','status'];

}