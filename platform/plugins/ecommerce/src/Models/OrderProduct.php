<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'ec_order_product';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'qty',
        'weight',
        'price',
        'tax_amount',
        'options',
        'restock_quantity',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    /**
     * @return string
     */
    public function getAmountFormatAttribute()
    {
        if ($this->order && $this->order->currency) {
            return format_price($this->price, $this->order->currency);
        }
        return human_price_text($this->price, null);
    }

    /**
     * @return string
     */
    public function getTotalFormatAttribute()
    {
        if ($this->order && $this->order->currency) {
            return format_price($this->price, $this->order->currency);
        }
        return human_price_text($this->price * $this->qty, null);
    }
}
