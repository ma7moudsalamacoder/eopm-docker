<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\System\Traits\FormatAuditDate;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use FormatAuditDate;
   /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
  
}
