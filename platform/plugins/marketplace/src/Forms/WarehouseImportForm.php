<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 02/07/2021
 * Time: 11:40 SA
 */

namespace Botble\Marketplace\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Marketplace\Http\Requests\ProductWarehouseRequest;
use Botble\Marketplace\Models\ProductWarehouse;


class WarehouseImportForm extends FormAbstract
{
    protected $customerRepository;
    public function __construct(CustomerInterface $customerRepository)
    {
        parent::__construct();
        $this->customerRepository = $customerRepository;
    }

    public function BuildForm(){
        $this
            ->setupModel(new ProductWarehouse)
            ->setValidatorClass(ProductWarehouseRequest::class)
            ->withCustomFields()
            ->setFormOption('template', 'plugins/marketplace::themes.dashboard.forms.base')
            ->setActionButtons(view('plugins/marketplace::themes.dashboard.forms.actions')->render())
            ->add('name', 'text', [
                'label'      => 'Tên kho',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Nhập tên kho',
                    'data-counter' => 120,
                ],
            ])
            ->add('customer_id', 'hidden', [
                'value'=> auth('customer')->user()->id,
                'attr'       => [
                    'placeholder'  => 'Vendor',
                ],
            ])
            ->add('address','text',[
                'label'=>'Địa chỉ chi tiết',
                'label_attr'=>['class'=>'control-label required'],
                'attr' => [
                    'placeholder'=>'Địa chỉ chi tiết của kho',
                ],
            ])
            ->add('description','textarea',[
                'label'=>'Mô tả ngắn',
                'label_attr'=> ['class'=>'control-label'],
                'attr'=>[
                    'placeholder'=>'Nhập mô tả ngắn',
                    'rows'=>4
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => 'Trạng thái',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }


}