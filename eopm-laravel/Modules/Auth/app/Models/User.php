<?php

namespace Modules\Auth\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Modules\System\Traits\FormatAuditDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class User extends \App\Models\User implements JWTSubject
{

    use Notifiable, HasRoles, LogsActivity;
   

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'address',
        'city_id',
        'country_id',
        'access_token'
    ];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    

}