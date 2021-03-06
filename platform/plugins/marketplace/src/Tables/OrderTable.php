<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Table\Abstracts\TableAbstract;
use EcommerceHelper;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    protected $request;

    /**
     * OrderTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param OrderInterface $orderRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, OrderInterface $orderRepository, Request $request)
    {
        $this->request = $request;
        $this->repository = $orderRepository;
        $this->setOption('id', 'table-vendor-orders');
        parent::__construct($table, $urlGenerator);

    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('payment_status', function ($item) {
                return $item->payment->status->label() ? $item->payment->status->toHtml() : '&mdash;';
            })
            ->editColumn('payment_method', function ($item) {
                return $item->payment->payment_channel->label() ? $item->payment->payment_channel->label() : '&mdash;';
            })
            ->editColumn('amount', function ($item) {
                return format_price($item->amount, $item->currency_id);
            })
            ->editColumn('shipping_amount', function ($item) {
                return format_price($item->shipping_amount, $item->currency_id);
            })
            ->editColumn('user_id', function ($item) {
                return $item->user->name ?? $item->address->name;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });

        if (EcommerceHelper::isTaxEnabled()) {
            $data = $data->editColumn('tax_amount', function ($item) {
                return format_price($item->tax_amount, $item->currency_id);
            });
        }

        $data = $data
            ->addColumn('operations', function ($item) {
                return view('plugins/marketplace::themes.dashboard.table.actions', [
                    'edit'   => 'marketplace.vendor.orders.edit',
                    'delete' => 'marketplace.vendor.orders.destroy',
                    'item'   => $item,
                ])->render();
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'status',
                'user_id',
                'created_at',
                'amount',
                'tax_amount',
                'currency_id',
                'shipping_amount',
                'payment_id',
            ])
            ->with(['user', 'payment'])
            ->where('is_finished', 1)
            ->where('store_id', auth('customer')->user()->store->id);
        //n???u c?? l???c tr???ng th??i
        if($this->request->get('status')){
            $query = $this->repository->getModel()
                ->select([
                    'id',
                    'status',
                    'user_id',
                    'created_at',
                    'amount',
                    'tax_amount',
                    'currency_id',
                    'shipping_amount',
                    'payment_id',
                ])
                ->with(['user', 'payment'])
                ->where('is_finished', 1)
                ->where('status',$this->request->get('status'))
                ->where('store_id', auth('customer')->user()->store->id);
        }

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        $columns = [
            'id'      => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'user_id' => [
                'title' => trans('plugins/ecommerce::order.customer_label'),
                'class' => 'text-left',
            ],
            'amount'  => [
                'title' => trans('plugins/ecommerce::order.amount'),
                'class' => 'text-center',
            ],
        ];

        if (EcommerceHelper::isTaxEnabled()) {
            $columns['tax_amount'] = [
                'title' => trans('plugins/ecommerce::order.tax_amount'),
                'class' => 'text-center',
            ];
        }

        $columns += [
            'shipping_amount' => [
                'title' => trans('plugins/ecommerce::order.shipping_amount'),
                'class' => 'text-center',
            ],
            'payment_method'  => [
                'name'  => 'payment_id',
                'title' => trans('plugins/ecommerce::order.payment_method'),
                'class' => 'text-center',
            ],
            'payment_status'  => [
                'name'  => 'payment_id',
                'title' => trans('plugins/ecommerce::order.payment_status_label'),
                'class' => 'text-center',
            ],
            'status'          => [
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'created_at'      => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-left',
            ],
        ];

        return $columns;
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('orders.deletes'), null, parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => OrderStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', OrderStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
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
