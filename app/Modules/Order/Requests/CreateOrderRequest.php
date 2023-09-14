<?php

namespace App\Modules\Order\Requests;

use App\Modules\Product\Rules\ProductHasIngredientsStock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', Rule::exists('products', 'id'), new ProductHasIngredientsStock],
            'products.*.quantity' => ['required', 'integer', 'gte:1'],
        ];
    }
}
