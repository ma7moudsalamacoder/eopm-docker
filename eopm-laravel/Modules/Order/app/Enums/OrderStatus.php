<?php

namespace Modules\Order\Enums;

enum OrderStatus:string {
    case PENDING = "pending";
    case PAID = "paid";
    case CANCELLED = "cancelled";
    case FIALED = "failed";
    case LOCKED = "locked";
}
