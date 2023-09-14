<?php

namespace App\Modules\Order\Pivots;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class OrderProduct extends Pivot
{
    use HasFactory;
}
