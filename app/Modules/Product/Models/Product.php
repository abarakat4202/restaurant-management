<?php

namespace App\Modules\Product\Models;

use App\Modules\Ingredient\Models\Ingredient;
use App\Modules\Product\Factories\ProductFactory;
use App\Modules\Product\Pivots\ProductIngredient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Product extends Model
{
    use HasFactory;

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
                ->withPivot(['amount', 'unit'])
                ->withTimestamps()
                ->using(ProductIngredient::class);
    }
}
