<?php

use App\Modules\Ingredient\Models\Ingredient;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
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
        Schema::create('order_product', function (Blueprint $table) {
            $table->foreignIdFor(Order::class)
                    ->nullable()
                    ->constrained()
                    ->cascadeOnDelete()
                    ->nullOnDelete();

            $table->foreignIdFor(Product::class)
                    ->nullable()
                    ->constrained()
                    ->cascadeOnDelete()
                    ->nullOnDelete();

            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
