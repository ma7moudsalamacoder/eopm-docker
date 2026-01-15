<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;
use Modules\Auth\Enums\UserRoles;
use Modules\Order\Enums\OrderStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Order\Events\OrderDeletedEvent;
use Modules\Order\Transformers\OrderResource;
use Modules\Order\Http\Requests\GetOrderRequest;
use Modules\System\Transformers\ActionsResponse;

class DeleteOrderAction
{
    use AsAction;
    public function handle(array $data):ActionsResponse {
        $currentOrder = Order::find($data['id']);
        $currentOrder->delete();
        event(new OrderDeletedEvent($currentOrder));
        $resource = OrderResource::make($currentOrder);
        return ActionsResponse::success(message:"Order has been deleted",resource:$resource);
    }

    public function asController(GetOrderRequest $request):ActionsResponse
    {
       return $this->handle($request->validated());
    }
}
