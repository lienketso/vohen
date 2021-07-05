<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 30/06/2021
 * Time: 1:37 CH
 */

namespace Botble\Marketplace\Models;


use Botble\Base\Models\BaseModel;

class Warehouse extends BaseModel
{
    protected $table = 'ec_warehouse';
    protected $fillable = ['name','customer_id','description','address','total_product','total_import','status'];
}