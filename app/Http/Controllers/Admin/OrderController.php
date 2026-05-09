<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderStatusRequest;
use DB;
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('items')
            ->filter($request->only(['search', 'status', 'payment_status']))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'         => $orders,
            'orderStatuses'  => OrderStatusEnum::cases(),
            'paymentStatuses'=> PaymentStatusEnum::cases(),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', [
            'order'          => $order,
            'orderStatuses'  => OrderStatusEnum::cases(),
            'paymentStatuses'=> PaymentStatusEnum::cases(),
        ]);
    }

public function update(UpdateOrderStatusRequest $request, Order $order)
{
    if ($order->status === OrderStatusEnum::COMPLETED) {
        return back()->with('error', 'Cannot update completed order');
    }

    DB::transaction(function () use ($request, $order) {
        $order->update($request->validated());
    });

    return back()->with('success', 'Order updated successfully');
}
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order moved to trash');
    }

    public function trashed()
    {
        $orders = Order::onlyTrashed()->latest()->paginate(15);
        return view('admin.orders.trashed', compact('orders'));
    }

    public function restore($id)
    {
        Order::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Order restored');
    }

    public function forceDelete($id)
    {
        Order::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Order permanently deleted');
    }
}