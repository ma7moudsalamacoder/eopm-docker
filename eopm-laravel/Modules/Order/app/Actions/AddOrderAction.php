<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Enums\OrderStatus;
use Modules\Inventory\Models\Product;
use Modules\Order\Events\AddOrderEvent;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Order\Events\AddOrderItemEvent;
use Modules\Order\Events\OrderCreatedEvent;
use Modules\Order\Transformers\OrderResource;
use Modules\Order\Events\OrderItemCreatedEvent;
use Modules\System\Transformers\ActionsResponse;
use Modules\Order\Http\Requests\OrderItemRequest;

class AddOrderAction
{
    use AsAction;
    public function handle(array $data): ActionsResponse
    {
        $message = "Order item added successfully to current order";
        $user = auth('api')->user();
        $currentOrder = Order::where('user_id', $user->id)
            ->where('status', OrderStatus::PENDING->value)
            ->first();
        if (!$currentOrder) {
            $currentOrder = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
            $message = "New order added successfully";
            event(new OrderCreatedEvent($currentOrder));
        }
        $product = Product::find(intval($data['product']));
        OrderItem::create([
            'order_id' => $currentOrder->id,
            'product' => $product->name,
            'price' => $product->price,
            'qty' => intval($data['qty'])
        ]);
        
        $currentOrder->relationLoaded('items');
        $resource = OrderResource::make($currentOrder);
        event(new OrderItemCreatedEvent($currentOrder));
        return ActionsResponse::success(message:$message, resource: $resource);
    }

    public function asController(OrderItemRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
