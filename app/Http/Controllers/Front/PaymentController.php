<?php

namespace App\Http\Controllers\Front;

use Stripe\Stripe;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\OrderStatusEnum;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use App\Enums\PaymentStatusEnum;
use App\Repositories\cart\CartRepositoryInterface;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment Success
    |--------------------------------------------------------------------------
    */

    public function success(
        Request $request,
        Order $order
    ) {
        try {

            /*
            |--------------------------------------------------------------------------
            | Set Stripe Secret Key
            |--------------------------------------------------------------------------
            */

            Stripe::setApiKey(
                config('services.stripe.secret')
            );

            /*
            |--------------------------------------------------------------------------
            | Get Session
            |--------------------------------------------------------------------------
            */

            $sessionId = $request->session_id;

            if (!$sessionId) {

                return redirect()
                    ->route('front.checkout.index')
                    ->with(
                        'error',
                        'Invalid payment session.'
                    );
            }

            $session = Session::retrieve(
                $sessionId
            );

            /*
            |--------------------------------------------------------------------------
            | Check Payment Status
            |--------------------------------------------------------------------------
            */

            if (
                $session->payment_status !== 'paid'
            ) {

                return redirect()
                    ->route('front.checkout.index')
                    ->with(
                        'error',
                        'Payment not completed.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Payment
            |--------------------------------------------------------------------------
            */

            if (
                $order->payment_status ===
                PaymentStatusEnum::COMPLETED
            ) {

                return redirect()
                    ->route(
                        'front.orders.show',
                        $order
                    )
                    ->with(
                        'success',
                        'Payment already completed.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Update Order + Payment
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $order,
                $session
            ) {

                /*
                |--------------------------------------------------------------------------
                | Decrease Stock
                |--------------------------------------------------------------------------
                */

                $this->decreaseStock($order);

                /*
                |--------------------------------------------------------------------------
                | Update Order
                |--------------------------------------------------------------------------
                */

                $order->update([

                    'payment_status' =>
                        PaymentStatusEnum::COMPLETED,

                    'status' =>
                        OrderStatusEnum::PROCESSING,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update Payment
                |--------------------------------------------------------------------------
                */

                $order->payment()->update([
                    'stripe_payment_intent' => $session->payment_intent,
                    'paid_at' => now(),
                    'status' =>
                        PaymentStatusEnum::COMPLETED,

                    'transaction_id' =>
                        $session->payment_intent,
                ]);
            });

            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */
            app(CartRepositoryInterface::class)->clearCart();
            // session()->forget('cart');



            /*
            |--------------------------------------------------------------------------
            | Redirect Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'front.orders.show',
                    $order
                )
                ->with(
                    'success',
                    'Payment completed successfully.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('front.checkout.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Cancel
    |--------------------------------------------------------------------------
    */

    public function cancel(Order $order)
    {
        return redirect()
            ->route('front.checkout.index')
            ->with(
                'error',
                'Payment canceled.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Decrease Product Stock
    |--------------------------------------------------------------------------
    */

    protected function decreaseStock(
        Order $order
    ): void {

        foreach ($order->items as $item) {

            $product = $item->product;

            if (
                $product->quantity <
                $item->quantity
            ) {

                throw new \Exception(
                    "{$product->name} out of stock."
                );
            }

            $product->decrement(
                'quantity',
                $item->quantity
            );
        }
    }
}