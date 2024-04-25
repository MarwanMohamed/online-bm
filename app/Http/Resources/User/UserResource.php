<?php

namespace App\Http\Resources\User;

use App\B5Digital\Transformers\DateFormatter;
use App\Http\Resources\MediaResource;
use App\Http\Resources\Permissions\PermissionsResource;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin \App\Models\User **/
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => new MediaResource($this->getFirstMedia('profile_picture')),
            'is_initial_password_changed' => !! $this->is_initial_password_changed,
            'is_active' => $this->is_active,
            'email_verified_at' => DateFormatter::make($this->email_verified_at),
            'is_email_verified' => $this->is_email_verified,
            'created_at' => DateFormatter::make($this->created_at),
            'roles' => RoleResource::collection($this->roles),
        ];
    }
}
