<?php

namespace App\Http\Resources\User;

use App\B5Digital\Transformers\DateFormatter;
use App\Http\Resources\MediaResource;
use App\Http\Resources\Permissions\PermissionsResource;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => new MediaResource($this->getFirstMedia('profile_picture')),
            'is_active' => $this->is_active,
            'is_email_verified' => $this->is_email_verified,
            'email_verified_at' => DateFormatter::make($this->email_verified_at),
            'is_initial_password_changed' => !! $this->is_initial_password_changed,
            'created_at' => DateFormatter::make($this->created_at),
            "email_change_requests"=>ChangeRequestResource::collection($this->emailChangeRequests),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionsResource::collection($this->getPermissionsViaRoles()),
            'social_accounts' => SocialAccountResource::collection($this->socialAccounts),
        ];
    }
}
