<?php

namespace App\Modules\Ingredient\Models;

use App\Modules\Ingredient\Enums\Unit;
use App\Modules\Ingredient\Factories\IngredientFactory;
use App\Modules\Ingredient\Observers\IngredientObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property int $full_stock
 * @property int $stock_level
 * @property Unit $unit
 * @property double $notify_stock_percentage
 * @property ?Carbon $notified_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'full_stock', 'stock_level', 'unit', 'notify_stock_percentage', 'notified_at'
    ];

    protected $casts = [
        'unit' => Unit::class,
        'notified_at' => 'datetime',
    ];

    public static function booted(): void
    {
        static::observe(IngredientObserver::class);
    }

    protected static function newFactory(): IngredientFactory
    {
        return IngredientFactory::new();
    }
}
