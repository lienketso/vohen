<?php

namespace Botble\Marketplace\Models;

use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mp_customer_withdrawals';

    /**
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'fee',
        'amount',
        'current_balance',
        'currency',
        'description',
        'payment_channel',
        'user_id',
        'status',
        'images',
        'bank_info',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'    => WithdrawalStatusEnum::class,
        'images'    => 'array',
        'bank_info' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    /**
     * @return bool
     */
    public function getVendorCanEditAttribute(): bool
    {
        return $this->status->getValue() === WithdrawalStatusEnum::PENDING;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function (&$withdrawal) {
            if ($withdrawal->id) {
                $statusOriginal = $withdrawal->getOriginal('status')->getValue();
                $status = $withdrawal->{$withdrawal->getTable() . '.status'} ?: $withdrawal->status->getValue();

                if ($statusOriginal != WithdrawalStatusEnum::PENDING) {
                    $withdrawal->status = $statusOriginal;
                    $withdrawal->{$withdrawal->getTable() . '.status'} = $statusOriginal;
                    return $withdrawal;
                }
                if ($statusOriginal == WithdrawalStatusEnum::PENDING &&
                    in_array($status, [WithdrawalStatusEnum::CANCELED, WithdrawalStatusEnum::REFUSED])) {
                    $vendor = $withdrawal->customer;
                    $vendorInfo = $vendor->vendorInfo;
                    $vendorInfo->balance += ($withdrawal->amount + $withdrawal->fee);
                    $vendorInfo->save();
                }
            }
        });
    }
}
