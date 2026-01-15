<?php

namespace Modules\Auth\Actions;

use Carbon\Carbon;
use Pest\Collision\Events;
use Modules\Auth\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Events\UserLoggedInEvent;
use Modules\Auth\Transformers\UserResource;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\System\Transformers\ActionsResponse;

class LoginAction
{
    use AsAction;

    public function handle(array $credentials): ActionsResponse
    {
        
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ActionsResponse::forbidden(message: 'Invalid Email or Password');
        }

        if (!$user->isActive()) {
            return ActionsResponse::forbidden(message: 'Your account is not active');
        }
       
        if ($user->access_token) {
            $payload = JWTAuth::manager()
                ->getJWTProvider()
                ->decode($user->access_token);
                if (($payload['exp'] ?? 0) > time()) {
                    JWTAuth::setToken($user->access_token)->invalidate(true);
                }
        }

        if (!$token = JWTAuth::fromUser($user)) {
            return ActionsResponse::failed(message: 'Could not create token');
        }
        $user->access_token = $token;
        $user->save();

        event(new UserLoggedInEvent($user));
        $resource = UserResource::make($user);
        return ActionsResponse::success(message: 'Login successful. All other sessions have been invalidated (If Exist).', resource: $resource);
    }

    public function asController(LoginRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
