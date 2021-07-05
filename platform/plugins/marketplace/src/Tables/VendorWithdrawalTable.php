<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Botble\Marketplace\Models\Withdrawal;

class VendorWithdrawalTable extends TableAbstract
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
    protected $hasCheckbox = false;

    /**
     * WithdrawalTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param WithdrawalInterface $revenueRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, WithdrawalInterface $revenueRepository)
    {
        $this->repository = $revenueRepository;
        $this->setOption('id', 'table-vendor-withdrawals');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return view('plugins/marketplace::withdrawals.tables.actions', compact('item'))->render();
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
            'mp_customer_withdrawals.id',
            'mp_customer_withdrawals.fee',
            'mp_customer_withdrawals.amount',
            'mp_customer_withdrawals.status',
            'mp_customer_withdrawals.currency',
            'mp_customer_withdrawals.created_at',
        ];

        $query = $model
            ->select($select)
            ->where('mp_customer_withdrawals.customer_id', auth('customer')->user()->id);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'              => [
                'name'  => 'mp_customer_withdrawals.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'fee'             => [
                'name'  => 'mp_customer_withdrawals.fee',
                'title' => trans('plugins/ecommerce::shipping.fee'),
                'class' => 'text-center',
            ],
            'amount'          => [
                'name'  => 'mp_customer_withdrawals.amount',
                'title' => trans('plugins/ecommerce::order.amount'),
                'class' => 'text-center',
            ],
            'status'     => [
                'name'  => 'mp_customer_withdrawals.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'created_at'      => [
                'name'  => 'mp_customer_withdrawals.created_at',
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

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('marketplace.vendor.withdrawals.create'));

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Withdrawal::class);
    }

}
