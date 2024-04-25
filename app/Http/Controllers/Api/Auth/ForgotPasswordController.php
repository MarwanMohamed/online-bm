<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\EmailNotVerifiedException;
use App\Http\Controllers\Auth\ForgotPasswordController as illuminateForgotPasswordController;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Auth
 */
class ForgotPasswordController extends illuminateForgotPasswordController
{
    use ResponseTrait;

    /**
     * Forget Password
     *
     * Send a reset link to the given user.
     *
     * @bodyParam email string required Example:admin@test.com
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "message": "We have e-mailed your password reset link!",
     * }
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        if ($user && !$user->is_email_verified) {
            throw new EmailNotVerifiedException(trans("messages.reset_password.email_not_verified"));
        }
        return parent::sendResetLinkEmail($request);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['message' => __('Kindly check your email inbox to complete the reset password steps')]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => trans($response),
            'status_code' => 400,
        ], 400);
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required', 'email', Rule::exists('users', 'email')
                    ->where('is_active', 1)
                    ->withoutTrashed(),
            ]
        ], [
            'email.email' => __(
                'Make sure to write legal email format'
            ),
            'email.exists' => __(
                'This email is not active or registered in the system, kindly connect the system admin'
            )
        ]);
    }
}
