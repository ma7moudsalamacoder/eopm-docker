<?php

namespace Modules\Auth\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\System\Transformers\ActionsResponse;

class HeartbeatAction
{
    use AsAction;

    public function handle() : ActionsResponse
    {
        return ActionsResponse::success(message: 'Service is alive');

    }
}
