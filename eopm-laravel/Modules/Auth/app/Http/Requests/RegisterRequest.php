<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Enums\UserRoles;
use Modules\Auth\Rules\CityBelongsToCountry;


class RegisterRequest extends FormRequest
{
 
    public function authorize(): bool
    {
        $user = $this->user('api');
    
        if ($this->routeIs('api.admin.register')) {
            return $user && $user->hasRole(UserRoles::ADMIN->value);
        }
    
        if ($this->routeIs('api.customer.register')) {
            return !$user || !$user->hasRole(UserRoles::CUSTOMER->value);
        }
    
        return true;
    }
    

    public function prepareForValidation(): void
    {
        if ($this->routeIs('api.admin.register')) {
            $this->merge(['scope' => 'admin']);
        } else {
            $this->merge(['scope' => 'customer']);
        }
    }


    public function rules(): array
    {
        return [
            'name' => "required|string|max:255",
            'email' => "required|string|email|max:255|unique:users,email",
            'password' => "required|string|min:8|confirmed",
            'phone' => "nullable|string|max:20",
            'status' => "required|in:active,inactive,suspended",
            'address' => "nullable|string|max:500",
            'country_id' => "required|int|exists:countries,id",
            'city_id' => [
                'required',
                'int',
                'exists:cities,id',
                new CityBelongsToCountry($this->input('country_id')),
            ],
            'scope' => "required|in:customer,admin",
        ];
    }

  
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'The password field is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'status.in' => 'Status must be one of the following: active, inactive, suspended.',
            'status.required' => 'The status field is required.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'country_id.required' => 'The country field is required.',
            'country_id.int' => 'Please select a valid country.',
            'country_id.exists' => 'The selected country does not exist.',
            'city_id.required' => 'The city field is required.',
            'city_id.int' => 'Please select a valid city.',
            'city_id.exists' => 'The selected city does not exist.',
            'city_id.cityBelongsToCountry' => 'The selected city is not valid for the given country.',
            'scope.required' => 'The scope field is required.',
            'scope.in' => 'Scope must be either customer or admin.',
        ];
    }

    
}