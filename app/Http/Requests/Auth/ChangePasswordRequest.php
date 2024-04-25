<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::defaults()
            ],
            'new_password_confirmation' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'new_password.min' => __('Password length must be from 8 to 15'),
            'new_password.between' => __('Password length must be from 8 to 15'),
            'new_password.confirmed' => __('Password not matched'),
        ];
    }

    /**
     * This method used to add parameters to scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'old_password' => [
                'description' => 'The authenticated user\'s current password',
                'example' => 'password',
            ],
            'new_password' => [
                'description' => 'The new password must be at least 15 characters.',
                'example' => 'Password_123456',
            ],
            'new_password_confirmation' => [
                'description' => 'The new password confirmation must me same the new password',
                'example' => 'Password_123456',
            ]
        ];
    }
}
