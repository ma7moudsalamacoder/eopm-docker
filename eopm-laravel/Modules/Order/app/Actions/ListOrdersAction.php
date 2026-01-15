<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;
use Modules\Inventory\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\System\Transformers\ActionsResponse;
use Modules\Order\Transformers\OrderResource;
use Modules\Order\Http\Requests\GetOrdersRequest;

class ListOrdersAction
{
    use AsAction;
    public function handle(array $data):ActionsResponse 
    {
        $limit = $data["limit"] ??10;
        $page = $data['page'] ?? 1;
        if($data['scope'] == 'all'){
            $user = auth("api")->user();
            $orders = Order::paginate($limit, ['*'], 'page', $page);
        }
        else{
            $user = auth("api")->user();
            $orders = Order::where("user_id",$user->id)
            ->paginate($limit, ['*'], 'page', $page);
        }

      
        $resource = OrderResource::collection($orders);
        return ActionsResponse::success(message:"Orders loaded successfully",resource:$resource);
    }
    public function asController(GetOrdersRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
