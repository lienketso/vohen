<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 07/07/2021
 * Time: 2:08 CH
 */

namespace Botble\Marketplace\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreEditRequest extends Request
{
    public function rules()
    {
        return [
            'name'        => 'required',
            'description' => 'max:400',
            'status'      => Rule::in(BaseStatusEnum::values()),
        ];
    }
}