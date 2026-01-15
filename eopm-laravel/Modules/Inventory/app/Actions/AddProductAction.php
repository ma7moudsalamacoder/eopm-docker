<?php

namespace Modules\Inventory\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Inventory\Http\Requests\ProductRequest;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Transformers\ProductResource;
use Modules\System\Transformers\ActionsResponse;

class AddProductAction
{
    use AsAction;
    public function handle(array $data):ActionsResponse {
        $product = Product::create([
            "name"=> $data["name"],
            "price"=> $data["price"],
            "stock_qty" => $data["stock_qty"]
        ]);
        $resource = ProductResource::make($product);
        return ActionsResponse::success(message:"Product added successfully",resource: $resource);
    }
    public function asController(ProductRequest $request) : ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
