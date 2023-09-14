<?php

namespace App\Modules\Product\Resources;

use App\Modules\Ingredient\Resources\IngredientResource;
use App\Modules\Order\Pivots\OrderProduct;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->whenPivotLoaded(new OrderProduct, fn() => $this->pivot->quantity),
            'ingredients' => IngredientResource::collection($this->whenLoaded('ingredients')),
        ];
    }
}
