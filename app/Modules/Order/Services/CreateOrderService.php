<?php

namespace App\Modules\Order\Services;

use App\Modules\Ingredient\Services\UpdateIngredientStockLevelService;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Pivots\OrderProduct;
use App\Modules\Product\Pivots\ProductIngredient;
use Illuminate\Support\Facades\DB;

final class CreateOrderService
{
    public function __construct(
        private UpdateIngredientStockLevelService $updateIngredientStockLevelService
    )
    {
        
    }
    
    public function handle(array $orderProducts): Order
    {
        return DB::transaction(function() use($orderProducts){
            $order = Order::create();
            $order->products()->attach($this->keyDataByProductId($orderProducts));
            $this->updateStockLevels($order);
            return $order;
        });
    }

    private function keyDataByProductId(array $orderProducts): array
    {
        return  array_reduce(
                    $orderProducts, 
                    function(array $carry, array $orderProductData)  {
                        $carry[$orderProductData['product_id']] = $orderProductData;
                        return $carry;
                    }, 
                    []
                );
    }

    private function updateStockLevels(Order $order): void
    {
        $order->load('products.ingredients');
            
        foreach( $order->products as $product )
        {
            /** @var OrderProduct */
            $orderProductPivot = $product->pivot;

            foreach($product->ingredients as $ingredient)
            {
                /** @var ProductIngredient */
                $productIngredientPivot = $ingredient->pivot;
                $subtractQuantity = $productIngredientPivot->amount * $orderProductPivot->quantity;
                $this->updateIngredientStockLevelService->sub($ingredient, $subtractQuantity);
            }
        }
    }
}