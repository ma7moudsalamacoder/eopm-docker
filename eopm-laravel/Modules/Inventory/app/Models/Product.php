<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\System\Traits\FormatAuditDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Inventory\Database\Factories\ProductFactory;

class Product extends Model
{
   use HasFactory, FormatAuditDate;
   protected $table = "products";

    protected $fillable = [
        'name',
        'price',
        'stock_qty'
    ];

   public function isStockAvailable(): bool
   {
       return $this->stock_qty > 0;
   }


}
