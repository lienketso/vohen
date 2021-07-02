<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 02/07/2021
 * Time: 12:14 CH
 */

namespace Botble\Marketplace\Http\Requests;


use Botble\Support\Http\Requests\Request;

class ProductWarehouseRequest extends Request
{
    public function rules(){
        return [
            'product_id'=>'required',
            'qty'=>'required|numeric|min:1'
        ];
    }
}