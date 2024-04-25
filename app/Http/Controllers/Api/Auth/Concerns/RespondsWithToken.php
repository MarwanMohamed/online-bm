<?php

namespace App\Http\Controllers\Api\Auth\Concerns;

trait RespondsWithToken
{
    protected function respondWithToken($token)
    {
        return $this->respond([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => guard()->factory()->getTTL() * 60,
        ]);
    }
}
