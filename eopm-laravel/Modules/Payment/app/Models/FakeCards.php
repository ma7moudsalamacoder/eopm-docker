<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Payment\Database\Factories\FakeCardsFactory;

class FakeCards extends Model
{
    use HasFactory;
    protected $table="fake_cards";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "holder",
        "card_number",
        "cvv",
        "type",
        "status"
    ];

    
}
