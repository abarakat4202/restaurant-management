<?php

namespace App\Modules\Ingredient\Observers;

use App\Modules\Ingredient\Events\LowStockLevelEvent;
use App\Modules\Ingredient\Models\Ingredient;

class IngredientObserver
{
    public function updated(Ingredient $ingredient): void
    {
        if( $ingredient->isClean('stock_level') )
            return;

        if( $ingredient->getOriginal('stock_level') <= $ingredient->stock_level)
            return;

        if( $ingredient->stock_level <= $ingredient->notify_stock_percentage * $ingredient->full_stock )
        {
            LowStockLevelEvent::dispatch($ingredient);
        }
    }
}
