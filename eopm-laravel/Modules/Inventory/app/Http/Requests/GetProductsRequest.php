<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
       
        if ($this->routeIs('api.inventory.products.view')||$this->routeIs('api.inventory.products.delete')) {
            if ($this->route('id')) {
                $this->merge([
                    'id' => $this->route('id') ?? 1
                ]);
            }
        }
        
        if ($this->routeIs('api.inventory.products.list')) {
            $this->merge([
                'page' => $this->query('page', 1),
                'limit' => $this->query('limit', 10)
            ]);
        }
    }


    public function rules(): array
    {
        if ($this->routeIs('api.inventory.products.view')||$this->routeIs('api.inventory.products.delete')) {
            return [
                'id' => 'required|integer|exists:products,id',
            ];
        }
        
        return [
            'page' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1|max:100',
        ];
    }
}
