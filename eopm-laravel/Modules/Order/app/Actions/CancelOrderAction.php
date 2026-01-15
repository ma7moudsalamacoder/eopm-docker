<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;
use Modules\Auth\Enums\UserRoles;
use Modules\Order\Enums\OrderStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Order\Events\OrderCancelledEvent;
use Modules\Order\Transformers\OrderResource;
use Modules\Order\Http\Requests\GetOrderRequest;
use Modules\System\Transformers\ActionsResponse;

class CancelOrderAction
{
    use AsAction;
    public function handle(array $data): ActionsResponse
    {
        $currentOrder = Order::query()
            ->where('id', $data['id'])
            ->where('status', OrderStatus::PENDING->value)
            ->first();
        if($currentOrder==null){
            return ActionsResponse::failed(message: "Order must be pending to be cancelled");    
        }    
        $currentOrder->status = OrderStatus::CANCELLED->value;
        $currentOrder->save();
        event(new OrderCancelledEvent($currentOrder));
        $resource = OrderResource::make($currentOrder);
        return ActionsResponse::success(message: "Order has been cancelled", resource: $resource);
    }

    public function asController(GetOrderRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
