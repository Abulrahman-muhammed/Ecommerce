<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Repositories\cart\CartRepositoryInterface;
use App\Notifications\OrderCreatedNotification;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Notification;
class CheckoutService
{
    public function placeOrder(array $data, CartRepositoryInterface $cartRepo): Order
    {
        return DB::transaction(function () use ($data, $cartRepo) {
            $cartItems = $cartRepo->getCartItems();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $preparedData = $this->prepareOrderData($data, $cartRepo->getCartTotal(), auth()->id());


            $order = Order::create($preparedData);

            foreach ($cartItems as $item) {
                $product = $item->product()->lockForUpdate()->first();

                if ($product->quantity < $item->quantity) {
                    throw new \Exception("{$product->name} is out of stock.");
                }

                $order->items()->create([
                    'product_id'   => $item->product_id,
                    'quantity'     => $item->quantity,
                    'price'        => $product->price,
                    'product_name' => $product->name,
                ]);

                $product->decrement('quantity', $item->quantity);
            }

            $cartRepo->clearCart();
            // notify admin
            $admin = Admin::first();
            $admin->notify(new OrderCreatedNotification($order));
            return $order;
        });
    }

    protected function prepareOrderData(array $data, float $total , ?int $userId): array
    {
        return array_merge($data, [
            'user_id'        => $userId,  
            'status'         => OrderStatusEnum::PENDING->value,
            'payment_status' => PaymentStatusEnum::PENDING->value,
            'total_amount'   => $total,
        ]);
    }


}