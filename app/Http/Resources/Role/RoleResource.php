<?php

namespace App\Http\Resources\Role;

use App\B5Digital\Transformers\DateFormatter;
use App\Http\Resources\Permissions\PermissionsListResource;
use App\Http\Resources\Permissions\PermissionsResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin \Spatie\Permission\Models\Role **/
class RoleResource extends JsonResource
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
            'display_name' => $this->display_name,
            'description' => $this->description,
            "permissions"=> PermissionsResource::collection($this->permissions)
        ];
    }

    public function additional(array $data)
    {
        return [
            'permissions' => PermissionsListResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
