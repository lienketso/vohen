<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class WithdrawalTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * WithdrawalTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param WithdrawalInterface $withdrawalRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        WithdrawalInterface $withdrawalRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $withdrawalRepository;

        if (!Auth::user()->hasAnyPermission(['marketplace.withdrawal.edit', 'marketplace.withdrawal.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer', function ($item) {
                if (!Auth::user()->hasPermission('customer.edit')) {
                    return $item->customer->name;
                }
                return Html::link(route('customer.edit', $item->customer->id), $item->customer->name);
            })
            ->editColumn('currency', function ($item) {
                return strtoupper($item->currency);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('marketplace.withdrawal.edit', 'marketplace.withdrawal.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'mp_customer_withdrawals.id',
            'mp_customer_withdrawals.customer_id',
            'mp_customer_withdrawals.amount',
            'mp_customer_withdrawals.currency',
            'mp_customer_withdrawals.created_at',
            'mp_customer_withdrawals.status',
        ];

        $query = $model->select($select)->with(['customer']);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'          => [
                'name'  => 'mp_customer_withdrawals.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'customer'    => [
                'name'  => 'mp_customer_withdrawals.customer_id',
                'title' => trans('plugins/marketplace::withdrawal.vendor'),
                'class' => 'text-left',
            ],
            'amount'      => [
                'name'  => 'mp_customer_withdrawals.amount',
                'title' => trans('plugins/marketplace::withdrawal.amount'),
                'class' => 'text-left',
            ],
            'currency'      => [
                'name'  => 'mp_customer_withdrawals.currency',
                'title' => trans('plugins/marketplace::withdrawal.currency'),
                'class' => 'text-left',
            ],
            'created_at'  => [
                'name'  => 'mp_customer_withdrawals.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'      => [
                'name'  => 'mp_customer_withdrawals.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('marketplace.withdrawal.deletes'), 'marketplace.withdrawal.destroy',
            parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'mp_customer_withdrawals.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => WithdrawalStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(WithdrawalStatusEnum::values()),
            ],
        ];
    }
}
