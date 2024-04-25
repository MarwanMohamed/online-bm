<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Enums\SocialProvider;
use Illuminate\Validation\Rules\Enum;

class SocialLoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
        ];
    }

    /**
     * This method used to add parameters to scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'code' => [
                'example' => '4/0AbUR2VNKJ07ILhSUvsW39dtS0BX2BgRkEaemUxc-xtnXxRXZ7peNMV2rE1F2re0EjB0x9Q',
            ]
        ];
    }
}
