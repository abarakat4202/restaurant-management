<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Factories\OrderFactory;
use App\Modules\Order\Pivots\OrderProduct;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Order extends Model
{
    use HasFactory;

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withTimestamps()
                    ->withPivot(['quantity'])
                    ->using(OrderProduct::class);
    }
}
