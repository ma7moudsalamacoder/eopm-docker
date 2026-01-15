<?php

namespace Modules\Order\Rules;

use Closure;
use Modules\Order\Models\Order;
use Modules\Auth\Enums\UserRoles;
use Modules\Order\Models\OrderItem;
use Modules\Inventory\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class CanAccessToOrder implements Rule
{
    public function __construct(
        protected string $scope
    ) {}

    public function passes($attribute, $value): bool
    {
        $user = auth('api')->user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }
        
        $item = match ($this->scope) {
            'OrderItem' => OrderItem::with('order.user')->find($value),
            'Order'     => Order::with('user')->find($value),
            default     => null,
        };
        
        if (!$item) {
            return false;
        }

        $orderUser = $this->scope === 'OrderItem'
            ? $item->order?->user
            : $item->user;

        return $orderUser?->id === $user->id;
    }

    public function message(): string
    {
        return 'You are not eligible to access';
    }
}
