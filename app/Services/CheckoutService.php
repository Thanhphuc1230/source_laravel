<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Mail\AlertOrder;
use App\Models\OrderProduct;
use App\Models\OrderShipping;
use App\Models\OrderStatus;
use App\Services\MailTemplateService;
use App\Services\MailConfigService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutService
{
    protected $cartService;
    protected $mailTemplateService;
    protected $mailConfigService;

    public function __construct(CartService $cartService, MailTemplateService $mailTemplateService, MailConfigService $mailConfigService)
    {
        $this->cartService = $cartService;
        $this->mailTemplateService = $mailTemplateService;
        $this->mailConfigService = $mailConfigService;
    }

    /**
     * Check if cart is valid for checkout
     *
     * @return bool
     */
    public function canCheckout(): bool
    {
        return !$this->cartService->isEmpty();
    }

    /**
     * Process checkout order
     *
     * @param Request $request
     * @return int|null Order status ID
     */
    public function processOrder(Request $request): ?int
    {
        try {
            DB::beginTransaction();

            // Create shipping info
            $shipping = $this->createShipping($request);

            // Create order status
            $orderStatus = $this->createOrderStatus($request, $shipping->id_order_shipping);

            // Create order products
            $this->createOrderProducts($orderStatus->id_order_status);

            // Send notification email
            $this->sendOrderNotification($orderStatus->id_order_status);

            DB::commit();

            return $orderStatus->id_order_status;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create shipping record
     *
     * @param Request $request
     * @return OrderShipping
     */
    private function createShipping(Request $request): OrderShipping
    {
        return OrderShipping::create([
            'f_name_order' => $request->f_name_order,
            'l_name_order' => $request->l_name_order,
            'uuid_order_shipping' => Str::uuid(),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'note' => $request->note,
        ]);
    }

    /**
     * Create order status record
     *
     * @param Request $request
     * @param int $shippingId
     * @return OrderStatus
     */
    private function createOrderStatus(Request $request, int $shippingId): OrderStatus
    {
        return OrderStatus::create([
            'uuid_order_status' => Str::uuid(),
            'shipping_id' => $shippingId,
            'payment_method' => $request->payment_method,
            'total' => $request->total,
        ]);
    }

    /**
     * Create order products from cart
     *
     * @param int $orderStatusId
     * @return void
     */
    private function createOrderProducts(int $orderStatusId): void
    {
        $cart = $this->cartService->getCart();

        foreach ($cart as $product) {
            OrderProduct::create([
                'uuid_order_product' => Str::uuid(),
                'order_status_id' => $orderStatusId,
                'product_id' => $product['id_product'],
                'quantity' => $product['qty'],
                'price' => $product['price'],
            ]);
        }
    }

    /**
     * Send order notification email
     *
     * @param int $orderId
     * @return void
     */
    private function sendOrderNotification(int $orderId): void
    {
        try {
            $orderDetails = $this->getOrderDetails($orderId);
            
            if (!$orderDetails) {
                Log::warning('Order details not found for order ID: ' . $orderId);
                return;
            }

            $template = $this->mailTemplateService->getActiveByType('order');
            
            if (!$template) {
                Log::warning('No active order template found');
                return;
            }

            $mailer = $this->mailConfigService->createMailer();
            
            // Send to admin
            $adminEmail = DB::table('tp_systems')->value('email_alert');
            if ($adminEmail) {
                $mailer->to($adminEmail)->send(new AlertOrder($orderDetails, $template));
                Log::info('Order notification email sent to admin: ' . $adminEmail);
            }

            // Send to customer
            $customerEmail = $orderDetails['shipping']->email;
            if ($customerEmail) {
                $mailer->to($customerEmail)->send(new AlertOrder($orderDetails, $template));
                Log::info('Order notification email sent to customer: ' . $customerEmail);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the order
            Log::error('Failed to send order notification email: ' . $e->getMessage());
        }
    }

    /**
     * Clear cart after successful order
     *
     * @return void
     */
    public function clearCartAfterOrder(): void
    {
        $this->cartService->clearCart();
    }

    /**
     * Get order details by ID
     *
     * @param int $orderId
     * @return array|null
     */
    public function getOrderDetails(int $orderId): ?array
    {
        $orderStatus = OrderStatus::with(['shipping', 'orderProducts.product'])
            ->where('id_order_status', $orderId)
            ->first();

        if (!$orderStatus) {
            return null;
        }

        return [
            'order' => $orderStatus,
            'shipping' => $orderStatus->shipping,
            'products' => $orderStatus->orderProducts,
            'total' => $orderStatus->total,
        ];
    }
}