<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $price = $this->input('price');
        if ($price !== null) {
            if (is_string($price)) {
                $price = str_replace([',', ' '], '', $price);
                $this->merge(['price' => $price]);
            }
            // if numeric, we'll validate it as-is and cast after validation
        }

        // Normalize stock quantity
        $qty = $this->input('stock_qty');
        if ($qty !== null) {
            if (is_string($qty)) {
                $qty = str_replace([' ', ','], '', $qty);
                $this->merge(['stock_qty' => $qty]);
            }
            // if numeric, we'll validate it as-is and cast after validation
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|decimal:0,2',
            'stock_qty' => 'required|integer|min:0',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.max' => 'Product name may not be greater than 255 characters.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a numeric value.',
            'price.min' => 'Price must be at least 0.',
            'price.decimal' => 'Price must be a valid amount with up to 2 decimal places.',
            'stock_qty.required' => 'Stock quantity is required.',
            'stock_qty.integer' => 'Stock quantity must be an integer.',
            'stock_qty.min' => 'Stock quantity cannot be negative.',
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
