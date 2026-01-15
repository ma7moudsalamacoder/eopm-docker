<?php

namespace Modules\Inventory\Actions;

use Modules\Inventory\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Inventory\Http\Requests\GetProductsRequest;
use Modules\Inventory\Transformers\ProductResource;
use Modules\System\Transformers\ActionsResponse;

class ListProductsAction
{
    use AsAction;
    public function handle(array $data):ActionsResponse 
    {
        if(!empty($data['id'])) {
            $product = Product::find($data['id']);
            $resource = ProductResource::make($product);
            return ActionsResponse::success(message:"Product loaded successfully",resource: $resource);
        }
        $limit = $data["limit"] ??10;
        $page = $data['page'] ?? 1;
        $products = Product::paginate($limit, ['*'], 'page', $page);
        $resource = ProductResource::collection($products);
        return ActionsResponse::success(message:"Products loaded successfully",resource:$resource);
    }
    public function asController(GetProductsRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
