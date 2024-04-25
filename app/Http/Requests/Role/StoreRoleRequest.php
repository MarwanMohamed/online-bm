<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;

class StoreRoleRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title"=>"required|string|min:1|max:150|unique:roles,name",
            "description"=>"required|string|min:10|max:500",
        ];
    }
}
