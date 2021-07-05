<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 30/06/2021
 * Time: 3:44 CH
 */

namespace Botble\Marketplace\Http\Requests;

use Illuminate\Validation\Rule;
use Botble\Support\Http\Requests\Request;

class WarehouseRequest extends Request
{
    public function rules(){
        return [
            'name'=>'required',
            'address'=>'required'
        ];
    }
}