<?php

namespace Modules\Inventory\Actions;

use Modules\Inventory\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\System\Transformers\ActionsResponse;
use Modules\Inventory\Transformers\ProductResource;
use Modules\Inventory\Http\Requests\GetProductsRequest;

class DeleteProductAction
{
    use AsAction;
    public function handle(array $data):ActionsResponse {
        $product = Product::find(intval($data["id"]));
        $resource = ProductResource::make($product);
        $product->delete();
        return ActionsResponse::success(message:"Product has been deleted successfully",resource:$resource);
    }

    public function asController(GetProductsRequest $request):ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
