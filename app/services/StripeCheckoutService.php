<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\Admin;
use App\Models\Order;
use Stripe\Checkout\Session;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Enums\PaymentStatusEnum;
use App\Notifications\OrderCreatedNotification;
use App\Repositories\cart\CartRepositoryInterface;

class StripeCheckoutService
{
    /*
    |--------------------------------------------------------------------------
    | Create Stripe Checkout Session
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data,
        CartRepositoryInterface $cartRepo
    ): array {

        return DB::transaction(function () use (
            $data,
            $cartRepo
        ) {

            $cartItems = $cartRepo->getCartItems();

            /*
            |--------------------------------------------------------------------------
            | Check Empty Cart
            |--------------------------------------------------------------------------
            */

            if ($cartItems->isEmpty()) {

                throw new \Exception(
                    'Cart is empty.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create Pending Order
            |--------------------------------------------------------------------------
            */

            $order = $this->createPendingOrder(
                $data,
                $cartRepo->getCartTotal()
            );

            /*
            |--------------------------------------------------------------------------
            | Create Order Items
            |--------------------------------------------------------------------------
            */

            $lineItems = $this->createOrderItems(
                $order,
                $cartItems
            );

            /*
            |--------------------------------------------------------------------------
            | Create Stripe Session
            |--------------------------------------------------------------------------
            */

            $session = $this->createStripeSession(
                $lineItems,
                $order
            );

            /*
            |--------------------------------------------------------------------------
            | Create Payment Record
            |--------------------------------------------------------------------------
            */

            $this->createPayment(
                $order,
                $session->id
            );

            /*
            |--------------------------------------------------------------------------
            | Notify Admin
            |--------------------------------------------------------------------------
            */

            $admin = Admin::first();

            if ($admin) {

                $admin->notify(
                    new OrderCreatedNotification($order)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Return Data
            |--------------------------------------------------------------------------
            */

            return [

                'order' => $order,

                'url' => $session->url,
            ];
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Create Pending Order
    |--------------------------------------------------------------------------
    */

    protected function createPendingOrder(
        array $data,
        float $total
    ): Order {

        return Order::create([

            'user_id' => auth()->id(),

            'payment_method' => 'stripe',

            'status' =>
                OrderStatusEnum::PENDING->value,

            'payment_status' =>
                PaymentStatusEnum::PENDING->value,

            'first_name' => $data['first_name'],

            'last_name' => $data['last_name'],

            'email' => $data['email']
                ?? auth()->user()?->email,

            'phone' => $data['phone'],

            'address' => $data['address'],

            'city' => $data['city'],

            'state' => $data['state'] ?? null,

            'postal_code' =>
                $data['postal_code'] ?? null,

            'country' =>
                $data['country'] ?? 'USA',

            'total_amount' => $total,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Order Items
    |--------------------------------------------------------------------------
    */

    protected function createOrderItems(
        Order $order,
        $cartItems
    ): array {

        $lineItems = [];

        foreach ($cartItems as $item) {

            $product = $item->product()
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Check Stock
            |--------------------------------------------------------------------------
            */

            if (
                $product->quantity <
                $item->quantity
            ) {

                throw new \Exception(
                    "{$product->name} is out of stock."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create Order Item
            |--------------------------------------------------------------------------
            */

            $order->items()->create([

                'product_id' => $product->id,

                'quantity' => $item->quantity,

                'price' => $product->price,

                'product_name' => $product->name,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Stripe Line Item
            |--------------------------------------------------------------------------
            */

            $lineItems[] = [

                'price_data' => [

                    'currency' => 'usd',

                    'unit_amount' =>
                        (int) ($product->price * 100),

                    'product_data' => [

                        'name' => $product->name,
                    ],
                ],

                'quantity' => $item->quantity,
            ];
        }

        return $lineItems;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Stripe Session
    |--------------------------------------------------------------------------
    */

    protected function createStripeSession(
        array $lineItems,
        Order $order
    ): Session {

        Stripe::setApiKey(
            config('services.stripe.secret')
        );

        return Session::create([

            'payment_method_types' => ['card'],

            'line_items' => $lineItems,

            'mode' => 'payment',

            'success_url' => route(
                'front.orders.payment.success',
                $order
            ) . '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => route(
                'front.orders.payment.cancel',
                $order
            ),

            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Pending Payment
    |--------------------------------------------------------------------------
    */

    protected function createPayment(
        Order $order,
        string $sessionId
    ): void {

        $order->payment()->create([

            'user_id' => auth()->id(),

            'payment_method' => 'stripe',

            'amount' => $order->total_amount,

            'stripe_session_id' => $sessionId,


            'status' =>
                PaymentStatusEnum::PENDING->value,
        ]);
    }
}