<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'page' => $this->query('page', 1),
            'limit' => $this->query('limit', 10)
        ]);
        if ($this->routeIs('api.orders.all.view')) {
            $this->merge(["scope" => "all"]);
        } else {
            $this->merge(["scope" => "user"]);
        }
    }


    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1|max:100',
            'scope' =>  'required|string|in:all,user',
        ];
    }
}
