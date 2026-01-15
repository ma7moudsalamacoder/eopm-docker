<?php
namespace Modules\Payment\Gateways;

use Modules\Payment\Models\FakeCards;
use Modules\Payment\Contracts\PaymentGatewayInterface;

class MGateway implements PaymentGatewayInterface
{
    public function charge(array $data): array
    {

        $checkDetails = FakeCards::query()
        ->where('card_number', $data['card']['card_number'])
        ->where('cvv', $data['card']['cvv'])
        ->where('valid', $data['card']['valid'])
        ->first('status');
        $status = !empty($checkDetails) ? $checkDetails->status :'declined';

        return [
            'status' => $status,
            'card'=> $data['card'],
            'transaction_id' => uniqid('demo_', true),
            'amount' => $data['amount'],
            'status_code' => $status == "in use" ? 200 : 500
        ];
    }
}
