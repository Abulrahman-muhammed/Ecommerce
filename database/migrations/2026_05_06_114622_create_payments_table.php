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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->string('payment_method'); 
        $table->string('transaction_id')->nullable(); 
        $table->string('stripe_session_id')->nullable();
        $table->string('stripe_payment_intent')->nullable();
        $table->decimal('amount', 10, 2);

        $table->tinyInteger('status');

        $table->timestamp('paid_at')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
