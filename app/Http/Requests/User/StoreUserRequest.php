<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use YlsIdeas\FeatureFlags\Facades\Features;
use App\Enums\Feature;

class StoreUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:1|max:100',
            'email'=> ['required', 'email', Rule::unique('users', 'email')->withoutTrashed()],
            'password' => Features::accessible(Feature::AUTO_GENERATE_PASSWORD()) ? "nullable": ['required', 'confirmed', Password::defaults()],
            'profile_picture' => 'mimes:jpeg,jpg,bmp,png,gif',
            'role_id' => 'nullable|exists:roles,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.email' => __('Make sure to write legal email format'),
            'email.unique' => __('This email has already been added in the system, Try to reset your password'),
        ];
    }
}
