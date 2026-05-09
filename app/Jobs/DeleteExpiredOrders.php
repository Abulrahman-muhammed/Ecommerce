<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Order;
use App\Enums\OrderStatusEnum;
class DeleteExpiredOrders implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Order::where('status', OrderStatusEnum::PENDING)
            ->where('created_at', '<', now()->subDays(7))
            ->delete();
    }
}
