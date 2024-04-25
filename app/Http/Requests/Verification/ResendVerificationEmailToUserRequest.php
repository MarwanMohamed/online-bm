<?php

namespace App\Http\Requests\Verification;

use App\Http\Requests\BaseRequest;

class ResendVerificationEmailToUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "user_id"=>"required|numeric|exists:users,id"
        ];
    }
}
