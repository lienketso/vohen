<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class RevenueTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * @var bool
     */
    protected $hasOperations = false;

    /**
     * @var bool
     */
    protected $hasCheckbox = false;

    /**
     * RevenueTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param RevenueInterface $revenueRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, RevenueInterface $revenueRepository)
    {
        $this->repository = $revenueRepository;
        $this->setOption('id', 'table-vendor-revenues');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('current_balance', function ($item) {
                return format_price($item->current_balance);
            })
            ->editColumn('order', function ($item) {
                if ($item->order) {
                    return Html::link(route('marketplace.vendor.orders.edit', $item->order->id),
                        get_order_code($item->order->id), ['target' => '_blank']);
                }
                return '&mdash;';
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'mp_customer_revenues.id',
            'mp_customer_revenues.sub_amount',
            'mp_customer_revenues.fee',
            'mp_customer_revenues.amount',
            'mp_customer_revenues.current_balance',
            'mp_customer_revenues.currency',
            'mp_customer_revenues.order_id',
            'mp_customer_revenues.created_at',
        ];

        $query = $model
            ->select($select)
            ->with(['order'])
            ->where('mp_customer_revenues.customer_id', auth('customer')->user()->id);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'              => [
                'name'  => 'mp_customer_revenues.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'order'           => [
                'name'  => 'mp_customer_revenues.order_id',
                'title' => trans('plugins/ecommerce::order.order'),
                'class' => 'text-center',
            ],
            'sub_amount'      => [
                'name'  => 'mp_customer_revenues.sub_amount',
                'title' => trans('plugins/ecommerce::order.sub_amount'),
                'class' => 'text-center',
            ],
            'fee'             => [
                'name'  => 'mp_customer_revenues.fee',
                'title' => trans('plugins/ecommerce::shipping.fee'),
                'class' => 'text-center',
            ],
            'amount'          => [
                'name'  => 'mp_customer_revenues.amount',
                'title' => trans('plugins/ecommerce::order.amount'),
                'class' => 'text-center',
            ],
            'currency'        => [
                'name'  => 'mp_customer_revenues.currency',
                'title' => trans('plugins/ecommerce::payment.currency'),
                'class' => 'text-center',
            ],
            'current_balance' => [
                'name'  => 'mp_customer_revenues.current_balance',
                'title' => trans('plugins/marketplace::marketplace.current_balance'),
                'class' => 'text-center',
            ],
            'created_at'      => [
                'name'  => 'mp_customer_revenues.created_at',
                'title' => trans('core/base::tables.created_at'),
                'class' => 'text-center',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
