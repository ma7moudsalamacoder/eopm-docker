<?php

namespace Modules\Payment\Enums;

enum PaymentMethods:string {
    case CASH = "Cash";
    case MG_CARD = "MGateway";
    case PAYPAL = "Paypal";
}
