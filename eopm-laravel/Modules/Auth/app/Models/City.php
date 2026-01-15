<?php

namespace Modules\Auth\Models;

use Modules\Auth\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Traits\FormatAuditDate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use FormatAuditDate;

    protected $table = 'cities';
    protected $fillable = [
        'country_id',
        'name',
    ];


    /**
     * Get the country that owns the city.
     */
    public function getCountryAttribute(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
