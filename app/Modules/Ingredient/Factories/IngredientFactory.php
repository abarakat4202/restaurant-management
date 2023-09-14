<?php

namespace App\Modules\Ingredient\Factories;

use App\Modules\Ingredient\Enums\Unit;
use App\Modules\Ingredient\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->unique()->word(),
            'full_stock' => $this->faker->randomNumber(2, true),
            'stock_level' => fn (array $attributes) => $attributes['full_stock'],
            'unit' => Unit::Gram,
            'notify_stock_percentage' => .50,
        ];
    }
}
