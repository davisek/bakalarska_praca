<?php

namespace App\Http\Resources\User;

use App\Http\Resources\EnumResources\EnumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at ?? null,
            'locale' => EnumResource::make($this->locale),
            'is_admin' => $this->is_admin,
            'dark_mode' => $this->dark_mode,
            'created_at' => $this->created_at,
        ];
    }
}
