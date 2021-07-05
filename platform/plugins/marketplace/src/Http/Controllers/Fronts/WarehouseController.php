<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 30/06/2021
 * Time: 1:52 CH
 */

namespace Botble\Marketplace\Http\Controllers\Fronts;


use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Forms\WarehouseForm;
use Botble\Marketplace\Forms\WarehouseImportForm;
use Botble\Marketplace\Http\Requests\WarehouseRequest;
use Botble\Marketplace\Repositories\Interfaces\WarehouseInterface;
use Botble\Marketplace\Tables\WarehouseImportTable;
use Botble\Marketplace\Tables\WarehouseTable;
use Illuminate\Http\Request;
use Mockery\Exception;

class WarehouseController extends BaseController
{
    protected $warehouseRepository;
    public function __construct(WarehouseInterface $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(WarehouseTable $table){
        page_title()->setTitle('Quản lý kho');
        return $table->render('plugins/marketplace::themes.dashboard.table.base');
    }

    public function create(FormBuilder $formBuilder){
        page_title()->setTitle('Thêm kho hàng mới');
        return $formBuilder->create(WarehouseForm::class)->renderForm();
    }

    public function store(WarehouseRequest $request, BaseHttpResponse $response){
        $store = $this->warehouseRepository->createOrUpdate($request->input());
        return $response
            ->setPreviousUrl(route('marketplace.vendor.warehouse'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit($id,FormBuilder $formBuilder, Request $request)
    {
        $store = $this->warehouseRepository->findOrFail($id);
        $store->fill($request->input());
        page_title()->setTitle('Sửa thông tin '. $store->name );
        return $formBuilder->create(WarehouseForm::class,['model' => $store])->renderForm();
    }

    public function update($id, WarehouseRequest $request, BaseHttpResponse $response){
        $store = $this->warehouseRepository->findOrFail($id);
        $store->fill($request->input());
        $this->warehouseRepository->createOrUpdate($store);
        return $response
            ->setPreviousUrl(route('marketplace.vendor.warehouse'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request,$id,BaseHttpResponse $response){
        try{
            $store = $this->warehouseRepository->findOrFail($id);
            $this->warehouseRepository->delete($store);
            return $response->setMessage('core/base::notices.delete_success_message');
        }catch (Exception $exception){
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function import($id, WarehouseImportTable $table){
        page_title()->setTitle('Nhập sản phẩm vào kho');
        return $table->render('plugins/marketplace::themes.dashboard.table.base');
    }


}