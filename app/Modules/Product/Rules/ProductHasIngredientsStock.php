<?php

namespace App\Modules\Product\Rules;

use App\Modules\Ingredient\Models\Ingredient;
use App\Modules\Product\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductHasIngredientsStock implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];
 
    // ...
 
    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $productId, Closure $fail): void
    {
        $product = Product::find($productId);
        
        if( empty($product) )
            return;

        $productQuantity = array_reduce($this->data['products'], function($quantity, $productData) use($productId){
            if($productData['product_id'] == $productId)
            {
                $quantity += $productData['quantity'];
            }
            return $quantity;
        }, 0);

        $product->ingredients->each(function(Ingredient $ingredient) use($productQuantity, $fail){
            if( $ingredient->stock_level < $ingredient->pivot->amount * $productQuantity )
            {
                $fail('No enough ingredients available to fulfill this product!');
                return false;
            }
        });
    }
}
