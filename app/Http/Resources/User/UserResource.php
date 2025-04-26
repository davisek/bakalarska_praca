<?php

namespace App\Http\Resources\User;

use App\Http\Resources\EnumResources\EnumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     @OA\Property(property="id", type="integer", description="User ID"),
 *     @OA\Property(property="name", type="string", description="User's first name"),
 *     @OA\Property(property="surname", type="string", description="User's last name"),
 *     @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Email verification timestamp"),
 *     @OA\Property(
 *         property="locale",
 *         ref="#/components/schemas/EnumResource",
 *         description="User's locale settings"
 *     ),
 *     @OA\Property(property="is_admin", type="boolean", description="Whether user has admin privileges"),
 *     @OA\Property(property="dark_mode", type="boolean", description="Whether dark mode is enabled"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Account creation timestamp")
 * )
 */
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
