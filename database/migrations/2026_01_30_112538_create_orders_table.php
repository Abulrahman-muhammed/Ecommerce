<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // user (guest allowed)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // order number
            $table->string('order_number')->unique();

            // payment
            $table->string('payment_method')->nullable();

            // status
            $table->tinyInteger('status')->default(0);
            // 0 = pending, 1 = completed, 2 = cancelled

            // payment
            $table->tinyInteger('payment_status')->default(0);
            // 0 = pending, 1 = paid, 2 = failed

            // customer info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone');

            // shipping
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country');

            // total
            $table->decimal('total_amount', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
