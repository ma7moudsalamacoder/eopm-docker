<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\OrderItem;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Order\Transformers\OrderResource;
use Modules\Order\Events\OrderItemDeletedEvent;
use Modules\System\Transformers\ActionsResponse;
use Modules\Order\Transformers\OrderItemResource;
use Modules\Order\Http\Requests\GetOrderItemRequest;

class DeleteOrderItemAction
{
    use AsAction;
    public function handle(array $data):ActionsResponse {
        $currentOrderItem = OrderItem::find($data['id']);
        $currentOrder = $currentOrderItem->order;
        $currentOrderItem->delete();
        event(new OrderItemDeletedEvent($currentOrderItem));
        $reources = [
            "order_id"=> $currentOrder->id,
            "order_item" => OrderItemResource::make($currentOrderItem)
        ];
        return ActionsResponse::success(message:"Order item has been deleted",resource:$reources);
    }

    public function asController(GetOrderItemRequest $request):ActionsResponse
    {
       return $this->handle($request->validated());
    }
}
