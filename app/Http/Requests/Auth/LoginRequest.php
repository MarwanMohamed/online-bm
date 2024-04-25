<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];
    }

    /**
     * This method used to add parameters to scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'example' => 'admin@test.come',
            ],
            'password' => [
                'example' => 'password',
            ],
        ];
    }
}
