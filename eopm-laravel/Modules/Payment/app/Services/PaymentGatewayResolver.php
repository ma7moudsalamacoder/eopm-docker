<?php

namespace Modules\Payment\Services;

use Modules\Payment\Gateways\MGateway;
use Modules\Payment\Contracts\PaymentGatewayInterface;

class PaymentGatewayResolver
{
    public static function resolve(string $method): PaymentGatewayInterface
    {
        return match ($method) {
            'MGateway' => new MGateway(),
            default => throw new \InvalidArgumentException('Unsupported payment method'),
        };
    }
}
