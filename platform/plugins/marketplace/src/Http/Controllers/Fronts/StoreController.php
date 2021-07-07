<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 07/07/2021
 * Time: 1:36 CH
 */

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Forms\VendorEditStoreForm;
use Botble\Marketplace\Http\Requests\StoreEditRequest;
use Botble\Marketplace\Http\Requests\StoreRequest;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Base\Forms\FormBuilder;
use Illuminate\Http\Request;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Events\UpdatedContentEvent;

class StoreController extends BaseController
{
    protected $storeRepository;

    public function __construct(StoreInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function edit(FormBuilder $formBuilder, Request $request){
        $customerID = auth('customer')->user()->id;
        $store = $this->storeRepository->firstOrNew(['customer_id'=>$customerID]);
        //create slug
        event(new BeforeEditContentEvent($request, $store));
        page_title()->setTitle('Cập nhật gian hàng' . ' "' . $store->name . '"');

        return $formBuilder->create(VendorEditStoreForm::class,['model'=>$store])->renderForm();

    }

    public function update(StoreEditRequest $request,BaseHttpResponse $response){
        $customerID = auth('customer')->user()->id;
        $store = $this->storeRepository->firstOrNew(['customer_id'=>$customerID]);
        $store->fill($request->input());

        $this->storeRepository->createOrUpdate($store);
        //update slug
        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setPreviousUrl(route('marketplace.vendor.dashboard'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

}