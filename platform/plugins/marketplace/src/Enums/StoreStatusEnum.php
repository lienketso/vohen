<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 20/07/2021
 * Time: 11:57 SA
 */

namespace Botble\Marketplace\Enums;
use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static WithdrawalStatusEnum PUBLISHED()
 * @method static WithdrawalStatusEnum CLOSED()
 */

class StoreStatusEnum extends Enum
{
    public const PUBLISHED = 'published';
    public const CLOSED = 'closed';

    public static $langPath = 'plugins/marketplace::store.statuses';

    public function toHtml()
    {
        switch ($this->value) {
            case self::PUBLISHED:
                return Html::tag('span', self::PUBLISHED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::CLOSED:
                return Html::tag('span', self::CLOSED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }

}