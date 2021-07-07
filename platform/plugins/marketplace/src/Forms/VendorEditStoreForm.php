<?php
/**
 * Created by PhpStorm.
 * User: wiseman
 * Date: 07/07/2021
 * Time: 1:51 CH
 */

namespace Botble\Marketplace\Forms;
use Botble\Base\Forms\FormAbstract;
use Botble\Marketplace\Http\Requests\StoreRequest;
use Botble\Marketplace\Models\Store;
use Botble\Base\Enums\BaseStatusEnum;

class VendorEditStoreForm extends FormAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function buildForm()
    {


        $this
            ->setupModel(new Store)
            ->setValidatorClass(StoreRequest::class)
            ->withCustomFields()
            ->setFormOption('template', 'plugins/marketplace::themes.dashboard.forms.base')
            ->setActionButtons(view('plugins/marketplace::themes.dashboard.forms.actions')->render())
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('email', 'email', [
                'label'      => trans('plugins/marketplace::store.forms.email'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/marketplace::store.forms.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/marketplace::store.forms.phone'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/marketplace::store.forms.phone_placeholder'),
                    'data-counter' => 15,
                ],
            ])
            ->add('rowClose', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('address', 'text', [
                'label'      => trans('plugins/marketplace::store.forms.address'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/marketplace::store.forms.address_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('city', 'text', [
                'label'      => trans('plugins/marketplace::store.forms.city'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/marketplace::store.forms.city_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('state', 'text', [
                'label'      => trans('plugins/marketplace::store.forms.state'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/marketplace::store.forms.state_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('country', 'text', [
                'label'      => trans('plugins/marketplace::store.forms.country'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/marketplace::store.forms.country_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
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
            ->add('content', 'editor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('core/base::forms.description_placeholder'),
                    'with-short-code' => false,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('logo', 'mediaImage', [
                'label'      => trans('plugins/marketplace::store.forms.logo'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('status');
    }

}