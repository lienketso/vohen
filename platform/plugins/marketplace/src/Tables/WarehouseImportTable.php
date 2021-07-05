<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 05/07/2021
 * Time: 2:03 CH
 */

namespace Botble\Marketplace\Tables;
use Botble\Marketplace\Repositories\Interfaces\WarehouseImportInterface;
use Botble\Marketplace\Repositories\Interfaces\WarehouseInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WarehouseImportTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    protected $warehouseID;
    /**
     * @var string
     */

    public function __construct(
        DataTables $table, UrlGenerator $urlGenerator,
        WarehouseImportInterface $warehouseImport,
        WarehouseInterface $warehouseRepository
    )
    {

        $this->repository = $warehouseImport;
        $this->setOption('id', 'table-vendor-warehouse-import');
        parent::__construct($table, $urlGenerator);
        $this->warehouseID = $table->getRequest()->route()->parameter('id');
    }

    public function ajax()
    {

        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('product_id', function ($item) {
                return $item->product_id ? $item->product->name : 'Null';
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('qty', function ($item) {
                return $item->qty ? $item->qty : '0';
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return view('plugins/marketplace::themes.dashboard.table.actions', [
                    'import-warehouse'   => '',
                    'item'   => $item,
                ])->render();
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function query()
    {

        $model = $this->repository->getModel()->with('product');
        $select = [
            'ec_product_warehouse.id',
            'ec_product_warehouse.product_id',
            'ec_product_warehouse.warehouse_id',
            'ec_product_warehouse.created_at',
            'ec_product_warehouse.status',
            'ec_product_warehouse.qty',
        ];

        $query = $model
            ->where('warehouse_id',$this->warehouseID)
            ->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'ec_product_warehouse.id',
                'title' => 'ID',
                'width' => '20px',
            ],
            'product_id'        => [
                'name'  => 'ec_product_warehouse.product_id',
                'title' => 'Sản phẩm',
                'class' => 'text-left',
            ],
            'qty'        => [
                'name'  => 'ec_product_warehouse.qty',
                'title' => 'Số lượng SP',
                'class' => 'text-left',
            ]
        ];
    }
}