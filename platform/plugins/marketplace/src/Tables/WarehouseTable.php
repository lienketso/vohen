<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;

use Botble\Marketplace\Models\Warehouse;
use Botble\Marketplace\Repositories\Interfaces\WarehouseInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class WarehouseTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * @var string
     */

    /**
     * ProductTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, WarehouseInterface $warehouseRepository)
    {
        $this->repository = $warehouseRepository;
        $this->setOption('id', 'table-vendor-warehouse');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                return $item->name ? $item->name : 'Null';
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('address', function ($item) {
                return $item->address ? $item->address : '&#8734;';
            })
            ->editColumn('total_product', function ($item) {
                return $item->total_product ? $item->total_product : '&mdash;';
            })
            ->editColumn('status', function ($item) {
                return $item->status;
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return view('plugins/marketplace::themes.dashboard.table.actions', [
                    'edit'   => 'marketplace.vendor.warehouse.edit',
                    'delete'=> 'marketplace.vendor.warehouse.delete',
                    'item'   => $item,
                ])->render();
            })
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
            'ec_warehouse.id',
            'ec_warehouse.name',
            'ec_warehouse.address',
            'ec_warehouse.created_at',
            'ec_warehouse.status',
            'ec_warehouse.total_product',
        ];

        $query = $model
            ->select($select)
            ->where('customer_id',auth('customer')->user()->id)
            ->where('status', 'published');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'ec_warehouse.id',
                'title' => 'ID',
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'ec_warehouse.name',
                'title' => 'Tên kho',
                'class' => 'text-left',
            ],
            'address'        => [
                'name'  => 'ec_warehouse.address',
                'title' => 'Địa chỉ',
                'class' => 'text-left',
            ],
            'total_product'        => [
                'name'  => 'ec_warehouse.total_product',
                'title' => 'Số lượng SP',
                'class' => 'text-left',
            ],
            'status'     => [
                'name'  => 'ec_warehouse.status',
                'title' => 'Trạng thái',
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('marketplace.vendor.warehouse.create'));
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Warehouse::class);
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('marketplace.vendor.warehouse.delete'),
            parent::bulkActions());
    }


}
