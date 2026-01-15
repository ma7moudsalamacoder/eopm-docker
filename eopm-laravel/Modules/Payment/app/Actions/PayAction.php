<?php

namespace Modules\Payment\Actions;

use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Order\Enums\OrderStatus;
use Modules\Payment\Enums\PaymentMethods;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Http\Requests\ChargeRequest;
use Modules\System\Transformers\ActionsResponse;
use Modules\Payment\Services\PaymentGatewayResolver;
use Modules\Payment\Transformers\PaymentResource;

use function Laravel\Prompts\error;

class PayAction
{
    use AsAction;
    public function handle(array $data): ActionsResponse
    {
        $order = Order::find(intval($data["order_id"]));
        $status = PaymentStatus::PENDING->value;
        $amount = $order->grandTotal;
        $user = auth("api")->user();
        $payer_name = $user->name;
        $result = [];
        if ($data['method'] !== PaymentMethods::CASH->value) {
            
            $gateway = PaymentGatewayResolver::resolve($data["method"]);
            $result = $gateway->charge([
                'card' => $data['card'],
                'amount' => $amount,
                'order_id' => $order->id,
            ]);
            $payer_name = $result['card']["holder"];
            $status = $result["status_code"]==200 ? PaymentStatus::PAID->value : PaymentStatus::FAILED->value;
        }else{
            $status = PaymentStatus::PAID->value;
        }
        $payment = [
            "order_id" => $data["order_id"],
            "payer_name" => $payer_name,
            "amount" => $amount,
            "method" => $data["method"],
            "payload" => $result,
            "status" => $status
        ];
        $paymentRecord = Payment::create($payment);
        if($status !== PaymentStatus::PAID->value){
            return ActionsResponse::failed(message:"payment failed",errors:["payment"=>$result]);
        }
        $order->status=OrderStatus::PAID->value;
        $order->save();
        $resource = PaymentResource::make($paymentRecord);
        return ActionsResponse::success(message:"payment done",resource:$resource);
    }

    public function asController(ChargeRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
