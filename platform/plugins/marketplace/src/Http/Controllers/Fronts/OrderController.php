<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Assets;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Http\Requests\ApplyCouponRequest;
use Botble\Ecommerce\Http\Requests\CreateShipmentRequest;
use Botble\Ecommerce\Http\Requests\RefundRequest;
use Botble\Ecommerce\Http\Requests\UpdateOrderRequest;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleShippingFeeService;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Marketplace\Repositories\Interfaces\VendorInfoInterface;
use Botble\Marketplace\Tables\OrderTable;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use OrderHelper;
use Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class OrderController extends BaseController
{
    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var ShipmentInterface
     */
    protected $shipmentRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderAddressRepository;

    /**
     * @var PaymentInterface
     */
    protected $paymentRepository;

    /**
     * @var StoreLocatorInterface
     */
    protected $storeLocatorRepository;

    /**
     * @var OrderProductInterface
     */
    protected $orderProductRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    protected $revenueRepository;

    protected $vendorInfoRepository;

    /**
     * @param OrderInterface $orderRepository
     * @param CustomerInterface $customerRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param ProductInterface $productRepository
     * @param ShipmentInterface $shipmentRepository
     * @param OrderAddressInterface $orderAddressRepository
     * @param PaymentInterface $paymentRepository
     * @param StoreLocatorInterface $storeLocatorRepository
     * @param OrderProductInterface $orderProductRepository
     * @param AddressInterface $addressRepository
     */
    public function __construct(
        OrderInterface $orderRepository,
        CustomerInterface $customerRepository,
        OrderHistoryInterface $orderHistoryRepository,
        ProductInterface $productRepository,
        ShipmentInterface $shipmentRepository,
        OrderAddressInterface $orderAddressRepository,
        PaymentInterface $paymentRepository,
        StoreLocatorInterface $storeLocatorRepository,
        OrderProductInterface $orderProductRepository,
        AddressInterface $addressRepository,
        RevenueInterface $revenueRepository,
        VendorInfoInterface $vendorInfoRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->productRepository = $productRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->paymentRepository = $paymentRepository;
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->addressRepository = $addressRepository;
        $this->revenueRepository = $revenueRepository;
        $this->vendorInfoRepository = $vendorInfoRepository;

        Assets::setConfig(config('plugins.marketplace.assets', []));
    }

    /**
     * @param Request $request
     * @param OrderTable $table
     * @return JsonResponse|View|Response
     * @throws Throwable
     */
    public function index(OrderTable $table)
    {
        page_title()->setTitle(__('Orders'));

        $orders = auth('customer')->user()->store->orders()->get();

        return $table->render('plugins/marketplace::themes.dashboard.table.base', compact('orders'));
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order.js',
            ])
            ->addScripts(['blockui', 'input-mask']);

        $order = $this->orderRepository
            ->getModel()
            ->where('id', $id)
            ->with(['products', 'user'])
            ->firstOrFail();

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        page_title()->setTitle(trans('plugins/ecommerce::order.edit_order', ['code' => get_order_code($id)]));

        $weight = 0;
        foreach ($order->products as $product) {
            if ($product && $product->weight) {
                $weight += $product->weight;
            }
        }

        $vendorInfo = $this->vendorInfoRepository->getFirstBy(['customer_id'=>auth('customer')->user()->id]);

        $defaultStore = get_primary_store_locator();

        return view('plugins/marketplace::themes.dashboard.orders.edit', compact('order', 'weight', 'defaultStore'));
    }

    /**
     * @param int $id
     * @param UpdateOrderRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, UpdateOrderRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->createOrUpdate($request->input(), ['id' => $id]);

        event(new UpdatedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));

        return $response
            ->setPreviousUrl(route('orders.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        try {
            $this->orderRepository->deleteBy(['id' => $id]);
            event(new DeletedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));
            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $order = $this->orderRepository->findOrFail($id);

            if ($order->store_id != auth('customer')->user()->store->id) {
                abort(404);
            }

            $this->orderRepository->delete($order);
            event(new DeletedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param int $orderId
     * @return BinaryFileResponse
     */
    public function getGenerateInvoice($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        $invoice = OrderHelper::generateInvoice($order);

        return response()->download($invoice)->deleteFileAfterSend();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postConfirm(Request $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($request->input('order_id'));

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        $order->is_confirmed = 1;
        if ($order->status == OrderStatusEnum::PENDING) {
            $order->status = OrderStatusEnum::PROCESSING;
        }

        $this->orderRepository->createOrUpdate($order);

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'confirm_order',
            'description' => trans('plugins/ecommerce::order.order_was_verified_by'),
            'order_id'    => $order->id,
            'user_id'     => 0,
        ]);

        $payment = $this->paymentRepository->getFirstBy(['order_id' => $order->id]);

        if ($payment) {
            $payment->user_id = 0;
            $payment->save();
        }

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('order_confirm')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'order_confirm',
                $order->user->email ?: $order->address->email
            );
        }

        return $response->setMessage(trans('plugins/ecommerce::order.confirm_order_success'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postResendOrderConfirmationEmail($id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        $result = OrderHelper::sendOrderConfirmationEmail($order);

        if (!$result) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.error_when_sending_email'));
        }

        return $response->setMessage(trans('plugins/ecommerce::order.sent_confirmation_email_success'));
    }

    /**
     * @param int $orderId
     * @param HandleShippingFeeService $shippingFeeService
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|Factory|View
     * @throws Throwable
     */
    public function getShipmentForm(
        $orderId,
        HandleShippingFeeService $shippingFeeService,
        Request $request,
        BaseHttpResponse $response
    ) {
        $order = $this->orderRepository->findOrFail($orderId);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        $weight = 0;
        if ($request->has('weight')) {
            $weight = $request->input('weight');
        } else {
            foreach ($order->products as $product) {
                if ($product && $product->weight) {
                    $weight += $product->weight;
                }
            }
        }

        $weight = $weight > 0.1 ? $weight : 0.1;

        $shippingData = [
            'address'     => $order->address->address,
            'country'     => $order->address->country,
            'state'       => $order->address->state,
            'city'        => $order->address->city,
            'weight'      => $weight,
            'order_total' => $order->amount,
        ];

        $shipping = $shippingFeeService->execute($shippingData);

        $storeLocators = $this->storeLocatorRepository->allBy(['is_shipping_location' => true]);

        $url = route('marketplace.vendor.orders.create-shipment', $order->id);

        if ($request->has('view')) {
            return view('plugins/ecommerce::orders.shipment-form',
                compact('order', 'weight', 'shipping', 'storeLocators', 'url'));
        }

        return $response->setData(view('plugins/ecommerce::orders.shipment-form',
            compact('order', 'weight', 'shipping', 'storeLocators', 'url'))->render());
    }

    /**
     * @param int $id
     * @param CreateShipmentRequest $request
     * @param BaseHttpResponse $response
     * @param ShipmentHistoryInterface $shipmentHistoryRepository
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function postCreateShipment(
        $id,
        CreateShipmentRequest $request,
        BaseHttpResponse $response,
        ShipmentHistoryInterface $shipmentHistoryRepository
    ) {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        $result = $response;
        $products = [];
        $weight = 0;
        foreach ($order->products as $orderProduct) {
            $products[] = [
                'name'     => $orderProduct->product_name,
                'weight'   => $orderProduct->weight ?? 0.1,
                'quantity' => $orderProduct->qty,
            ];
            $weight += $orderProduct->weight ?? 0.1;
        }

        $weight = $weight > 0.1 ? $weight : 0.1;

        $shipment = [
            'order_id'   => $order->id,
            'user_id'    => 0,
            'weight'     => $weight,
            'note'       => $request->input('note'),
            'cod_amount' => $request->input('cod_amount') ?? ($order->payment->status !== PaymentStatusEnum::COMPLETED ? $order->amount : 0),
            'cod_status' => 'pending',
            'type'       => $request->input('method'),
            'status'     => ShippingStatusEnum::DELIVERING,
            'price'      => $order->shipping_amount,
            'store_id'   => $request->input('store_id'),
        ];

        $store = $this->storeLocatorRepository->findById($request->input('store_id'));

        if (!$store) {
            $defaultStore = $this->storeLocatorRepository->getFirstBy(['is_primary' => true]);
            $shipment['store_id'] = $defaultStore ?? ($defaultStore ? $defaultStore->id : null);
        }

        switch ($request->input('method')) {
            default:
                $result = $result->setMessage(trans('plugins/ecommerce::order.order_was_sent_to_shipping_team'));
                break;
        }

        if (!$result->isError()) {
            $this->orderRepository->createOrUpdate([
                'status'          => OrderStatusEnum::DELIVERING,
                'shipping_method' => $request->input('method'),
                'shipping_option' => $request->input('option'),
            ], compact('id'));

            $shipment = $this->shipmentRepository->createOrUpdate($shipment);

            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            if ($mailer->templateEnabled('customer_delivery_order')) {
                OrderHelper::setEmailVariables($order);
                $mailer->sendUsingTemplate(
                    'customer_delivery_order',
                    $order->user->email ?: $order->address->email
                );
            }

            $this->orderHistoryRepository->createOrUpdate([
                'action'      => 'create_shipment',
                'description' => $result->getMessage() . ' ' . trans('plugins/ecommerce::order.by_username'),
                'order_id'    => $id,
                'user_id'     => 0,
            ]);

            $shipmentHistoryRepository->createOrUpdate([
                'action'      => 'create_from_order',
                'description' => trans('plugins/ecommerce::order.shipping_was_created_from'),
                'shipment_id' => $shipment->id,
                'order_id'    => $id,
                'user_id'     => 0,
            ]);

            //update fee ship cho mp_vendor_info
//            $vendorInfo = $this->vendorInfoRepository->getFirstBy(['customer_id'=>auth('customer')->user()->id]);
//            $data = [
//                'total_fee'=> $vendorInfo->total_fee + $order->shipping_amount
//            ];
//            $this->vendorInfoRepository->update([
//                'customer_id'=>auth('customer')->user()->id
//            ], $data);

        }

        return $result;
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postCancelShipment($id, BaseHttpResponse $response)
    {
        $shipment = $this->shipmentRepository->createOrUpdate(['status' => ShippingStatusEnum::CANCELED],
            compact('id'));

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_shipment',
            'description' => trans('plugins/ecommerce::order.shipping_was_canceled_by'),
            'order_id'    => $shipment->order_id,
            'user_id'     => 0,
        ]);

        return $response
            ->setData([
                'status'      => ShippingStatusEnum::CANCELED,
                'status_text' => ShippingStatusEnum::CANCELED()->label(),
            ])
            ->setMessage(trans('plugins/ecommerce::order.shipping_was_canceled_success'));
    }

    /**
     * @param int $id
     * @param AddressRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postUpdateShippingAddress($id, AddressRequest $request, BaseHttpResponse $response)
    {
        $address = $this->orderAddressRepository->createOrUpdate($request->input(), compact('id'));

        if (!$address) {
            abort(404);
        }

        return $response
            ->setData([
                'line'   => view('plugins/ecommerce::orders.shipping-address.line', compact('address'))->render(),
                'detail' => view('plugins/ecommerce::orders.shipping-address.detail', compact('address'))->render(),
            ])
            ->setMessage(trans('plugins/ecommerce::order.update_shipping_address_success'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postCancelOrder($id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        $this->orderRepository->createOrUpdate(['status' => OrderStatusEnum::CANCELED, 'is_confirmed' => true],
            compact('id'));

        foreach ($order->products as $orderProduct) {
            $product = $orderProduct->product;
            $product->quantity += $orderProduct->qty;

            if ($product->is_variation) {
                $originalProduct = $product->original_product;
                $originalProduct->quantity += $orderProduct->qty;
                $originalProduct->save();
            }

            $product->save();
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_order',
            'description' => trans('plugins/ecommerce::order.order_was_canceled_by'),
            'order_id'    => $order->id,
            'user_id'     => 0,
        ]);

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('customer_cancel_order')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'customer_cancel_order',
                $order->user->email ?: $order->address->email
            );
        }

        return $response->setMessage(trans('plugins/ecommerce::order.customer.messages.cancel_success'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function postConfirmPayment($id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id, ['payment']);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        if ($order->status === OrderStatusEnum::PENDING) {
            $order->status = OrderStatusEnum::PROCESSING;
        }

        $this->orderRepository->createOrUpdate($order);

        $payment = $order->payment;

        $payment->status = PaymentStatusEnum::COMPLETED;

        $this->paymentRepository->createOrUpdate($payment);

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('order_confirm_payment')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'order_confirm_payment',
                $order->user->email ?: $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'confirm_payment',
            'description' => trans('plugins/ecommerce::order.payment_was_confirmed_by', [
                'money' => format_price($order->amount),
            ]),
            'order_id'    => $order->id,
            'user_id'     => 0,
        ]);

//        //create customer_revenua_table
//        $revenua = $this->revenueRepository->createOrUpdate([
//            'customer_id'=>auth('customer')->user()->id,
//            'order_id'=>$order->id,
//            'sub_amount'=>$order->amount,
//            'fee'=>0,
//            'amount'=>$order->amount,
//            'current_balance'=>$order->amount,
//            'currency'=>'VND',
//            'description'=>'Cập nhật doanh thu'
//        ]);
//        //update mp_vendor_info
//        $vendorInfo = $this->vendorInfoRepository->getFirstBy(['customer_id'=>auth('customer')->user()->id]);
//        $data = [
//            'balance'=> $vendorInfo->balance + $order->amount,
//            'total_fee'=> $vendorInfo->total_fee + $order->shipping_amount,
//            'total_revenue'=>$vendorInfo->total_revenue + $order->amount
//        ];
//        $this->vendorInfoRepository->update([
//            'customer_id'=>auth('customer')->user()->id
//        ], $data);


        return $response->setMessage(trans('plugins/ecommerce::order.confirm_payment_success'));
    }

    /**
     * @param int $id
     * @param RefundRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postRefund($id, RefundRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->store_id != auth('customer')->user()->store->id) {
            abort(404);
        }

        if ($request->input('refund_amount') > ($order->payment->amount - $order->payment->refunded_amount)) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.refund_amount_invalid', [
                    'price' => format_price($order->payment->amount - $order->payment->refunded_amount,
                        get_application_currency()),
                ]));
        }

        $hasError = false;
        foreach ($request->input('products', []) as $productId => $quantity) {
            $orderProduct = $this->orderProductRepository->getFirstBy([
                'product_id' => $productId,
                'order_id'   => $id,
            ]);
            if ($quantity > ($orderProduct->qty - $orderProduct->restock_quantity)) {
                $hasError = true;
                $response
                    ->setError()
                    ->setMessage(trans('plugins/ecommerce::order.number_of_products_invalid'));
                break;
            }
        }

        if ($hasError) {
            return $response;
        }

        $payment = $order->payment;
        if (!$payment) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.cannot_found_payment_for_this_order'));
        }

        $payment->refunded_amount += $request->input('refund_amount');
        if ($payment->refunded_amount == $payment->amount) {
            $payment->status = PaymentStatusEnum::REFUNDED;
        }
        $payment->refund_note = $request->input('refund_note');
        $this->paymentRepository->createOrUpdate($payment);

        foreach ($request->input('products', []) as $productId => $quantity) {
            $product = $this->productRepository->findById($productId);
            if ($product && $product->with_storehouse_management) {
                $product->quantity += $quantity;
                $this->productRepository->createOrUpdate($product);
            }

            $orderProduct = $this->orderProductRepository->getFirstBy([
                'product_id' => $productId,
                'order_id'   => $id,
            ]);
            if ($orderProduct) {
                $orderProduct->restock_quantity += $quantity;
                $this->orderProductRepository->createOrUpdate($orderProduct);
            }
        }

//        $vendorInfo = $this->vendorInfoRepository->getFirstBy(['customer_id'=>auth('customer')->user()->id]);
//        //Xóa bảng doanh thu mp_customer_revenue
//        $this->revenueRepository->deleteBy(['order_id'=>$order->id]);
//        //hoàn tiền cập nhật mp_vendor_info
//
//        $data = [
//            'balance'=> $vendorInfo->balance - $request->input('refund_amount'),
//            'total_fee' => $vendorInfo->total_fee - $order->shipping_amount,
//            'total_revenue'=> $vendorInfo->total_revenue - $request->input('refund_amount'),
//        ];
//        $this->vendorInfoRepository->update(['customer_id'=>auth('customer')->user()->id],$data);

        if ($request->input('refund_amount', 0) > 0) {
            $this->orderHistoryRepository->createOrUpdate([
                'action'      => 'refund',
                'description' => trans('plugins/ecommerce::order.refund_success_with_price', [
                    'price' => format_price($request->input('refund_amount')),
                ]),
                'order_id'    => $order->id,
                'user_id'     => 0,
                'extras'      => json_encode([
                    'amount' => $request->input('refund_amount'),
                    'method' => $payment->payment_channel ?? PaymentMethodEnum::COD,
                ]),
            ]);



        }





        return $response->setMessage(trans('plugins/ecommerce::order.refund_success'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param HandleShippingFeeService $shippingFeeService
     * @return BaseHttpResponse
     */
    public function getAvailableShippingMethods(
        Request $request,
        BaseHttpResponse $response,
        HandleShippingFeeService $shippingFeeService
    ) {
        $weight = 0;
        $orderAmount = 0;

        foreach ($request->input('products', []) as $productId) {
            $product = $this->productRepository->findById($productId);
            if ($product) {
                $weight += $product->weight;
                $orderAmount += $product->front_sale_price;
            }
        }

        $weight = $weight > 0.1 ? $weight : 0.1;

        $shippingData = [
            'address'     => $request->input('address'),
            'country'     => $request->input('country'),
            'state'       => $request->input('state'),
            'city'        => $request->input('city'),
            'weight'      => $weight,
            'order_total' => $orderAmount,
        ];

        $shipping = $shippingFeeService->execute($shippingData);

        $result = [];
        foreach ($shipping as $key => $shippingItem) {
            foreach ($shippingItem as $subKey => $subShippingItem) {
                $result[$key . ';' . $subKey . ';' . $subShippingItem['price']] = [
                    'name'  => $subShippingItem['name'],
                    'price' => format_price($subShippingItem['price'], null, true),
                ];
            }
        }

        return $response->setData($result);
    }

    /**
     * @param ApplyCouponRequest $request
     * @param HandleApplyCouponService $handleApplyCouponService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postApplyCoupon(
        ApplyCouponRequest $request,
        HandleApplyCouponService $handleApplyCouponService,
        BaseHttpResponse $response
    ) {
        $result = $handleApplyCouponService->applyCouponWhenCreatingOrderFromAdmin($request);

        if ($result['error']) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage($result['message']);
        }

        return $response
            ->setData(Arr::get($result, 'data', []))
            ->setMessage(trans('plugins/ecommerce::order.applied_coupon_success',
                ['code' => $request->input('coupon_code')]));
    }
}
