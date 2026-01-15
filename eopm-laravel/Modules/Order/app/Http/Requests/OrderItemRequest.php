<?php

namespace Modules\Order\Http\Requests;

use Modules\Order\Rules\ProductHasStock;
use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product' => [
                'required',
                'int',
                'exists:products,id'
            ],
            'qty' => [
                'required',
                'integer',
                'min:1',
                new ProductHasStock($this->input('product'))
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product.required' => 'Product id is required.',
            'product.int' => 'Product id must be a integer.',
            'product.exists' => 'Product does not exist.',
            'qty.ProductHasStock' => 'The product does not have enough stock.',
            'qty.required' => 'Quantity is required.',
            'qty.integer' => 'Quantity must be an integer.',
            'qty.min' => 'Quantity must be at least 1.',
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
