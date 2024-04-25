<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Auth\ResetPasswordController as illuminateResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\IFTTTHandler;
use YlsIdeas\FeatureFlags\Facades\Features;
use App\Enums\Feature;

/**
 * @group Auth
 */
class ResetPasswordController extends illuminateResetPasswordController
{
    /**
     * Reset Password
     *
     * Reset the given user's password.
     *
     * @bodyParam token string The token will expire after 24 hours. required Example:bc8f073715567b444c73c7363c514d5ed34fb
     * @bodyParam email string required Example:admin@test.com
     * @bodyParam password string required Example:password
     * @bodyParam password_confirmation string required Example:password
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "message": "Your password has been reset!",
     * }
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        return parent::reset($request);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        if (Features::accessible(Feature::AUTO_LOGIN_AFTER_RESET_PASSWORD())){
            $user= $this->broker()->getUser($this->credentials($request));
            Auth::login($user);
        }

        return response()->json(['message' => trans($response)]);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => trans($response),
            'status_code' => 400,
        ], 400);
    }
}
