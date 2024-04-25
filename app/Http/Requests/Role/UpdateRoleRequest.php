<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckMandatryPermissionsPermission;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route('role')->id ;
        return [
            "title"=>["required", "string", "min:1", "max:150", Rule::unique('roles', 'name')
                    ->ignore($id)],
            "description"=>"required|string|min:10|max:500",
            "permissions"=>"required|array",
            "permissions.*"=>["required", "exists:permissions,id", new CheckMandatryPermissionsPermission],
        ];
    }
}
