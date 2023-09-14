<?php

namespace App\Modules\Product\Seeders;

use App\Modules\Ingredient\Enums\Unit;
use App\Modules\Ingredient\Models\Ingredient;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Product::count())
            return;

        $productIngredients = [
            ['name' => 'Beef', 'full_stock' => 20000, 'pivot_amount' => 150, 'unit' => Unit::Gram ],
            ['name' => 'Cheese', 'full_stock' => 5000, 'pivot_amount' => 30, 'unit' => Unit::Gram],
            ['name' => 'Onion', 'full_stock' => 1000, 'pivot_amount' => 20, 'unit' => Unit::Gram],
        ];

        $productFactory = Product::factory();

        foreach($productIngredients as $pi)
        {
            $productFactory = $productFactory->hasAttached(
                Ingredient::factory()
                            ->state(Arr::except($pi, 'pivot_amount'))
                            ->create(), 
                [ 'amount' => $pi['pivot_amount'], 'unit' => $pi['unit'] ]
            );
        }
            
        $productFactory->create(['name' => 'Burger']);
    }
}
