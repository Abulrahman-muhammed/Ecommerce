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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('quantity')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                    ->nullable()
                    ->references('id')
                    ->on('categories')
                    ->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
