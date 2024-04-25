<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Auth
 * @authenticated
 */
class ChangePasswordController extends Controller
{
    use ResponseTrait;

    /**
     * Change Password
     *
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "data": {
     *          "message": "Password has been changed successfully",
     *          "data": null,
     *          "status_code": 200,
     *      }
     *  }
     *
     * @param \App\Http\Requests\Auth\ChangePasswordRequest $request
     * @throws \Illuminate\Validation\ValidationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ChangePasswordRequest $request)
    {
        if (! Hash::check($request->old_password, me()->password)) {
            throw ValidationException::withMessages([
                'old_password' => trans('messages.user.checkPassword'),
            ]);
        }

        me()->update(['password' => Hash::make($request->new_password)]);

        me()->forceFill(['is_initial_password_changed' => false])->save();

        return $this->respondWithSuccess(trans('messages.user.passwordChange'));
    }
}
