<?php

use App\Modules\Ingredient\Models\Ingredient;
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
        Schema::create('product_ingredient', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete(); 

            $table->foreignIdFor(Ingredient::class)
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                    
            $table->unsignedInteger('amount');
            $table->char('unit', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ingredient');
    }
};
