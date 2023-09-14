<?php

namespace App\Modules\Ingredient\Services;

use App\Modules\Ingredient\Models\Ingredient;

final class UpdateIngredientStockLevelService
{

    protected $withoutEvents = false;
    
    public function sub(Ingredient $ingredient, int $quantity): bool
    {
        return $this->update($ingredient, $quantity * -1);
    }

    public function add(Ingredient $ingredient, int $quantity): bool
    {
        return $this->update($ingredient, $quantity);
    }

    public function withoutEvents(): self
    {
        $this->withoutEvents = true;
        return $this;
    }

    private function update(Ingredient $ingredient, int $quantity): bool
    {
        $updateData = [
            'stock_level' => $ingredient->stock_level + $quantity,
        ];

        if( $this->withoutEvents )
        {
            return $ingredient->updateQuietly($updateData);
        }

        return $ingredient->update($updateData);
    }
}