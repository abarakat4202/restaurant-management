<?php

namespace App\Modules\Ingredient\Resources;

use App\Modules\Ingredient\Models\Ingredient;
use App\Modules\Product\Pivots\ProductIngredient;
use App\Modules\Product\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Ingredient */
class IngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->whenPivotLoaded(new ProductIngredient, fn() => $this->pivot->amount),
        ];
    }
}
