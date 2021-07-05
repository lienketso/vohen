<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\Marketplace\Tables\VendorWithdrawalTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Botble\Marketplace\Forms\VendorWithdrawalForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Marketplace\Http\Requests\VendorWithdrawalRequest;
use Botble\Marketplace\Http\Requests\VendorEditWithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;

class WithdrawalController
{
    /**
     * @var WithdrawalInterface
     */
    protected $withdrawalRepository;

    /**
     * WithdrawalController constructor.
     * @param WithdrawalInterface $withdrawalRepository
     */
    public function __construct(WithdrawalInterface $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }

    /**
     * @param WithdrawalTable $table
     * @return JsonResponse|View|Response
     */
    public function index(VendorWithdrawalTable $table)
    {
        page_title()->setTitle(__('Withdrawals'));

        return $table->render('plugins/marketplace::themes.dashboard.table.base');
    }

    
    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder, BaseHttpResponse $response)
    {
        $user = auth('customer')->user();
        $fee = get_marketplace_setting('fee_withdrawal', 0);
        if ($user->balance <= $fee || !$user->bank_info) {
            return $response
                    ->setError()
                    ->setNextUrl(route('marketplace.vendor.withdrawals.index'))
                    ->setMessage(__('Insufficient balance or no bank information'));
        }
        page_title()->setTitle(__('Withdrawal request'));

        return $formBuilder->create(VendorWithdrawalForm::class)->renderForm();
    }


    /**
     * @param VendorWithdrawalRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(VendorWithdrawalRequest $request, BaseHttpResponse $response) {
        $fee = get_marketplace_setting('fee_withdrawal', 0);
        $vendor = auth('customer')->user();
        $vendorInfo = $vendor->vendorInfo;

        DB::beginTransaction();
        try {
            $this->withdrawalRepository->create([
                'fee'           => $fee,
                'amount'        => $request->input('amount'),
                'customer_id'   => $vendor->getKey(),
                'currency'      => get_application_currency()->title,
                'bank_info'     => $vendorInfo->bank_info,
                'description'   => $request->input('description'),
                'current_balance'   => $vendorInfo->balance,
            ]);
            
            $vendorInfo->balance -= ($request->input('amount') + $fee);
            $vendorInfo->save();
    
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        
        return $response
            ->setPreviousUrl(route('marketplace.vendor.withdrawals.index'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $customer = auth('customer')->user();
        $withdrawal = $this->withdrawalRepository->getFirstBy([
                'id'            => $id,
                'customer_id'   => $customer->id,
                'status'        => WithdrawalStatusEnum::PENDING,
            ]);
        if (!$withdrawal) {
            abort(404);
        }
        page_title()->setTitle(__('Update withdrawal request #' . $id));

        return $formBuilder->create(VendorWithdrawalForm::class, ['model' => $withdrawal])->renderForm();
    }

    /**
     * @param int $id
     * @param VendorEditWithdrawalRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, VendorEditWithdrawalRequest $request, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();
        $withdrawal = $this->withdrawalRepository->getFirstBy([
                'id'            => $id,
                'customer_id'   => $customer->id,
                'status'        => WithdrawalStatusEnum::PENDING,
            ]);
        if (!$withdrawal) {
            abort(404);
        }

        $status = WithdrawalStatusEnum::PENDING;
        if ($request->get('cancel')) {
            $status = WithdrawalStatusEnum::CANCELED;
            $response->setNextUrl(route('marketplace.vendor.withdrawals.show', $withdrawal->id));
        }

        $withdrawal->fill([
            'status'        => $status,
            'description'   => $request->input('description'),
        ]);

        $this->withdrawalRepository->createOrUpdate($withdrawal);

        return $response
            ->setPreviousUrl(route('marketplace.vendor.withdrawals.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function show($id, FormBuilder $formBuilder, Request $request)
    {
        $customer = auth('customer')->user();
        $withdrawal = $this->withdrawalRepository
            ->getFirstBy([
                ['id', '=', $id],
                ['customer_id', '=', $customer->id],
                ['status', '!=', WithdrawalStatusEnum::PENDING]
            ]);
        if (!$withdrawal) {
            abort(404);
        }
        page_title()->setTitle(__('View withdrawal request #' . $id));

        return $formBuilder->create(VendorWithdrawalForm::class, ['model' => $withdrawal])->renderForm();
    }
}
