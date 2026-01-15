<?php

namespace Modules\Auth\Models;

use Modules\Auth\Models\City;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Traits\FormatAuditDate;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use FormatAuditDate;
    protected $table = 'countries';
    protected $fillable = [
        'name',
    ];


    /**
     * Get the cities for the country.
     */
    public function getCitiesAttribute(): HasMany
    {
        return $this->hasMany(City::class);
    }


}
