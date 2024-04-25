<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Rules\ChangeEmailCheck;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user') ? $this->route('user')->id : auth()->id();
        $meRoute = (strpos($this->url(), 'me') !== false);

        return [
            'name' => 'required|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->withoutTrashed()
                    ->ignore($id),
            ],
            'profile_picture' => 'mimes:jpeg,jpg,bmp,png,gif',
            'role_id' => (! $meRoute) ? 'required|exists:roles,id' : 'nullable',
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
            'email.unique' => __('This email has already been added in the system'),
        ];
    }
}
