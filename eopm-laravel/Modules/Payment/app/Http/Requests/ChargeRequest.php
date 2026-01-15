<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Order\Rules\CanAccessToOrder;
use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
                new CanAccessToOrder('Order'),
            ],
            'method' => [
                'required',
                'string',
                Rule::in(['MGateway', 'Cash']),
            ],
            'card' => [
                'exclude_unless:method,MGateway',
                'required',
                'array',
            ],
        
            'card.holder' => [
                'exclude_unless:method,MGateway',
                'required',
                'string',
                'max:255',
            ],
        
            'card.card_number' => [
                'exclude_unless:method,MGateway',
                'required',
                'string',
                'digits_between:13,19',
            ],
        
            'card.cvv' => [
                'exclude_unless:method,MGateway',
                'required',
                'string',
                'digits_between:3,4',
            ],
        
            'card.valid' => [
                'exclude_unless:method,MGateway',
                'required',
                'string',
            ],
        ];
    }


    public function messages()
    {
        return [
            "order_id.required"=> "order id must be provided",
            "order_id.integer"=> "order id must be integer",
            "order_id.exists"=> "order does not exist",
            "order_id.CanAccessToOrder"=> "you are not allowed to access this order",
            "method.required"=> "amount must be provided",
            "method.string"=> "amount must be integer",
            "method.in"=> "payment method not supported, it must be MGateway or Cash",
            "card.required_if"=> "card must be provided",
            "card.array"=> "card must be array",
            "card.holder.required_if"=> "card holder must be provided",
            "card.holder.string"=> "card holder must be string",
            "card.holder.max"=> "card holder must be less than 255 characters",
            "card.card_number.required_if"=> "card number must be provided",
            "card.card_number.string"=> "card number must be string",
            "card.card_number.digits_between"=> "card number must be between 13 and 19 digits",
            "card.cvv.required_if"=> "card cvv must be provided",
            "card.cvv.string"=> "card cvv must be string",
            "card.cvv.digits_between"=> "card cvv must be between 3 and 4 digits",            
            "card.valid.required_if"=> "card valid must be provided",
            "card.valid.string"=> "card valid must be string",
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
