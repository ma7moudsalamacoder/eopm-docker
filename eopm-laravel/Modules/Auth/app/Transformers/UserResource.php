<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? null,
            'status' => $this->status,
            'roles' => $this->roles->pluck('name'),
            'address' => $this->address ?? null,
            'city' => $this->city ? [
                'id' => $this->city->id,
                'name' => $this->city->name,
                ] : null,
            
            'country' => $this->country ? [
                'id' => $this->country->id,
                'name' => $this->country->name,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'access_token' => $this->access_token ?? null,
        ];
    }
}