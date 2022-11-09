<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Events\UserUsedCouponAtPurchaseEvent;
use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Payment\PaymentRequest;
use Gentcmen\Jobs\SendOrderEmailJob;
use Gentcmen\Models\Coupon;
use Gentcmen\Models\Order;
use Gentcmen\Models\OrderProduct;
use Gentcmen\Models\OrderStatus;
use Gentcmen\Models\Product;
use Gentcmen\Models\User;
use Gentcmen\Notifications\UserPlacedOrderNotification;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Notification;

class PaymentController extends ApiController
{
    public function makePayment(PaymentRequest $request): \Illuminate\Http\JsonResponse
    {
        $orderTransaction = DB::transaction(function () use ($request) {
            $order = Order::create([
                'user_id' => auth('api')->id(),
                'coupon_id' => $request->coupon_id,
                'order_status_id' => OrderStatus::STATUS_CREATED,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_delivery_type' => $request->billing_delivery_type,
                'billing_payment_type' => $request->billing_payment_type,
		        'billing_city' => $request->billing_city,
        	    'billing_user_name' =>$request->billing_user_name,
	            'billing_street' =>$request->billing_street,
		        'billing_house' =>$request->billing_house,
        	    'billing_apartment' => $request->billing_apartment,
		        'message' => $request->message,
		        'message_from_user' => $request->message_from_user,
		        'department' => $request->department,
                'grand_total' => 0
            ]);

            $grandTotal = 0;
            $arrProduct = [];
            foreach($request->products as $customerProduct) {

                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $customerProduct['id'],
                    'quantity' => $customerProduct['quantity'],
                    'coupon_id' => $customerProduct['coupon_id'] ?? null
                ]);

                $productRecord = Product::select('id', 'cost')
                    ->with('discounts', 'productCategories.discounts')
                    ->find($customerProduct['id']);

                // Note first of all we need to apply all possible discounts for product
                $productDiscount = $productRecord->discounts->sum('amount');
                $productCategoryDiscount = $productRecord->productCategories->reduce(function ($acc, $item) {
                    return $acc +  $item->discounts->sum('amount');
                }, 0);

                $productPrice = $this->applyPercent($productRecord->cost, $productDiscount + $productCategoryDiscount) * $customerProduct['quantity'];

                // Only after apply product discounts, we can apply coupon discount
                if (isset($customerProduct['coupon_id'])) {
                    $coupon = Coupon::find($customerProduct['coupon_id']);
                    event(new UserUsedCouponAtPurchaseEvent($coupon, auth('api')->id()));
                    $productPrice = $this->applyPercent($productPrice, $coupon->discount);
                }

                $grandTotal += $productPrice;
                array_push($arrProduct, [
                    'product' => $orderProduct->product()->get()->first(),
                    'qty' => $customerProduct['quantity'],
                    'cost' => $productPrice
                ]);
            }

            // Apply discount for all order
            if ($request->coupon_id) {
                $coupon = Coupon::find($request->coupon_id);
                event(new UserUsedCouponAtPurchaseEvent($coupon, auth('api')->id()));
                $grandTotal = $this->applyPercent($grandTotal, $coupon->discount);
            }

            $order->update([
               'grand_total' => $grandTotal
            ]);

            $order->products = $arrProduct;

            return $order;
        });

        dispatch(new SendOrderEmailJob([
            'id' => $orderTransaction->id,
            'total' => $orderTransaction->grand_total,
            'products' => $orderTransaction->products,
            'email' => $orderTransaction->billing_email
        ]));

        $this->notifyAdmins($orderTransaction);

        return $this->respondCreated();
    }

    /**
     * Handle successful redirect
     * @param Request $request
     */

    public function paymentSuccess(Request $request)
    {
        dd($request->all());
        // send email to user after successful payment
        // dispatch(new SendOrderEmailJob($orderTransaction->billing_email));
    }

    /**
     * Calculate percentage of a number
     * @param $number
     * @param $percent
     * @return float|int
     */

    private function calculatePercentOfNumber($number, $percent): float|int
    {
        return round($number * ($percent / 100), 2);
    }

    /**
     * Apply percent to number
     * @param $number
     * @param $percent
     * @return float|int
     */

    private function applyPercent($number, $percent): float|int
    {
        return $number - $this->calculatePercentOfNumber($number, $percent);
    }

    /**
     * Notify admins about new order
     * @param $orderDetails
     */

    private function notifyAdmins($orderDetails)
    {
        $notificationDetails = [
            'products' => [],
            'billing_email' => $orderDetails->billing_email,
            'order_id' => $orderDetails->id,
            'user_id' => $orderDetails->user_id,
            'billing_phone' => $orderDetails->billing_phone,
            'grand_total' => $orderDetails->grand_total,
	    'billing_delivery_type' => $orderDetails->billing_delivery_type,
	    'billing_payment_type' => $orderDetails->billing_payment_type
        ];

        Notification::send(User::fetchAdmins(), new UserPlacedOrderNotification($notificationDetails));
    }
}
