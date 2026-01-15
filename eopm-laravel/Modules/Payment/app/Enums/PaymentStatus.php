<?php

namespace Modules\Payment\Enums;

enum PaymentStatus:string {
    case PENDING = "pending";
    case PAID = "paid";
    case FAILED = "failed";
}
