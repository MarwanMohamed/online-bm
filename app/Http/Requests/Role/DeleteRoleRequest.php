<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckNewRoleDifferentFromOldRole;
use Illuminate\Validation\Rule;

class DeleteRoleRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "new_role"=>["required", new CheckNewRoleDifferentFromOldRole],
        ];
    }
}
