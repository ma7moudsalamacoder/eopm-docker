<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Models\User;
use Modules\Auth\Enums\UserRoles;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Transformers\UserResource;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\System\Transformers\ActionsResponse;

class RegisterAction
{
    use AsAction;

    public function handle(array $data): ActionsResponse
    {
        $currentUser = auth("api")->user();
        if ($currentUser && !$currentUser->hasRole(UserRoles::ADMIN->value) && UserRoles::ADMIN->name !== strtoupper($data['scope'])) {
            return ActionsResponse::forbidden(message: 'Only Administrators can register new users with elevated privileges.');
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
            'address' => $data['address'] ?? null,
            'country_id' => $data['country_id'],
            'city_id' => $data['city_id'],
        ]);
        $user->assignRole(UserRoles::tryByName(strtoupper($data['scope']))?->value ?? 'Customer');
        event(new \Modules\Auth\Events\UserRegisteredEvent($user));
        $resource = UserResource::make($user);
        return ActionsResponse::success(message: 'User registered successfully, you can login with the created account now', resource: $resource);
    }

    public function asController(RegisterRequest $request): ActionsResponse
    {
        return $this->handle($request->validated());
    }
}
