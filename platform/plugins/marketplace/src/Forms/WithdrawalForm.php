<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Http\Requests\WithdrawalRequest;
use Botble\Marketplace\Models\Withdrawal;

class WithdrawalForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Withdrawal)
            ->setValidatorClass(WithdrawalRequest::class)
            ->withCustomFields()
            ->add('amount', 'text', [
                'label'      => trans('plugins/marketplace::withdrawal.forms.amount'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'disabled' => 'disabled',
                ],
            ])
            ->add('fee', 'text', [
                'label'      => trans('plugins/marketplace::withdrawal.forms.fee'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'disabled' => 'disabled',
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('bankInfo', 'html', [
                'html' => view('plugins/marketplace::withdrawals.bank-info', [
                    'bankInfo' => $this->getModel()->bank_info,
                    'title'    => __('Bank information'),
                ])->render(),
            ])
            ->add('images[]', 'mediaImages', [
                'label'      => trans('plugins/ecommerce::products.form.image'),
                'label_attr' => ['class' => 'control-label'],
                'values'     => $this->getModel() ? $this->getModel()->images : [],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => WithdrawalStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
