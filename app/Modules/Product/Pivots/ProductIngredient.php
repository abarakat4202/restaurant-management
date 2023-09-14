<?php

namespace App\Modules\Product\Pivots;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

/**
 * @property int $product_id
 * @property int $ingredient_id
 * @property int $amount
 * @property Unit $unit
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class ProductIngredient extends Pivot
{
    use HasFactory;
    
    protected $table = 'product_ingredient';

}
