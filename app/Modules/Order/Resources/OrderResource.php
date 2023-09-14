<?php

namespace App\Modules\Order\Resources;

use App\Modules\Order\Models\Order;
use App\Modules\Product\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
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
            'timestamp' => $this->created_at?->timestamp,
            'items' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
