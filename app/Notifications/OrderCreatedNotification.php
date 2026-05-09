<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\Order;

class OrderCreatedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin'),
        ];
    }


    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'order_created',
            'title'   => 'New Order 🛒',
            'message' => "Order #{$this->order->order_number} by {$this->order->customer_name}.",
            'order_id'=> $this->order->id,
            'url'     => route('admin.orders.show', $this->order),
            'icon'    => 'shopping-cart',
            'color'   => 'success',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type'    => 'order_created',
            'title'   => 'New Order 🛒',
            'message' => "Order #{$this->order->order_number} by {$this->order->customer_name}.",
            'order_id'=> $this->order->id,
            'url'     => route('admin.orders.show', $this->order),
            'icon'    => 'shopping-cart',
            'color'   => 'success',
        ]);
    }
}