<?php

namespace Modules\Auth\Rules;

use Closure;
use Modules\Auth\Models\City;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class CityBelongsToCountry implements Rule
{
    protected $countryId;

    public function __construct($countryId)
    {
        $this->countryId = $countryId;
    }

    public function passes($attribute, $value)
    {
        // Check if the city exists and belongs to the given country
        $city = City::find($value);

        if (!$city) {
            return false; // City does not exist
        }

        return $city->country_id == $this->countryId;
    }

    public function message()
    {
        return 'The selected city is not valid for the given country.';
    }
}
