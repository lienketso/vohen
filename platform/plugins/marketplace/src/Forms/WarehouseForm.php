<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 30/06/2021
 * Time: 3:32 CH
 */

namespace Botble\Marketplace\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Marketplace\Http\Requests\WarehouseRequest;
use Botble\Marketplace\Models\Warehouse;

class WarehouseForm extends FormAbstract
{
    protected $customerRepository;

    public function __construct(CustomerInterface $customerRepository)
    {
        parent::__construct();
        $this->customerRepository = $customerRepository;

    }

    public function buildForm(){

        $this
            ->setupModel(new Warehouse)
            ->setValidatorClass(WarehouseRequest::class)
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