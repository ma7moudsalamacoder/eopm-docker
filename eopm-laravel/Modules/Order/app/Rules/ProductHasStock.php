<?php

namespace Modules\Order\Rules;

use Closure;
use Modules\Inventory\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductHasStock implements Rule
{

    public function __construct(
        protected int $productId
    ) {}

    public function passes($attribute, $value)
    {
        $product = Product::find($this->productId);

        if (!$product) {
            return false;
        }
        
        return $product->stock_qty >= $value;
    }

    public function message()
    {
        return 'The product does not have enough stock.';
    }
}
