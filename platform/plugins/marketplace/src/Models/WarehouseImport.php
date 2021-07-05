<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 05/07/2021
 * Time: 3:12 CH
 */

namespace Botble\Marketplace\Models;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Product;

class WarehouseImport extends BaseModel
{
    protected $table = 'ec_product_warehouse';
    protected $fillable = ['product_id','warehouse_id','qty','created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

}