<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verification\ResendVerificationEmailRequest;
use App\Http\Requests\Verification\ResendVerificationEmailToUserRequest;
use App\Models\User;
use App\Models\UserVerify;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserAction;

/**
 * @group Auth
 */
class VerificationController extends Controller
{
    use ResponseTrait;

    protected $service;

    /**
     * VerificationController constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->service= $userService;
    }

    /**
     * Verify User Email
     *
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "data": {
     *          "message": "Great!. Your e-mail is now verified.",
     *          "data": null,
     *      }
     *  }
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request, $hash)
    {
        $verifyUser = UserVerify::where('token', $hash)->first();
        if (!$verifyUser){
            return $this->setStatusCode(Response::HTTP_FORBIDDEN)
                ->respondWithSuccess(trans("messages.verification.email_not_valid"), ['redirect_to_login'=>true]);
        }

        if ($verifyUser->expire_at->lt(Carbon::now())){
            return $this->setStatusCode(Response::HTTP_FORBIDDEN)->respondWithSuccess(trans("messages.verification.email_link_expired"), ['redirect_to_resend'=>true]);
        }
        switch ($verifyUser->action_type){

            case UserAction::CHANGE_EMAIL_REQUEST:
                return $this->respondWithSuccess($this->service->handleChangeEmailRequest($verifyUser));

            case UserAction::EMAIL_VERIFICATION:
            default:
                return $this->respondWithSuccess($this->service->verifyUserEmail($verifyUser));
        }

    }


    /**
     * Resend Verification Email
     *
     * @bodyParam email string required Example:khaled@test.com
     *
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "data": {
     *          "message": "An Email has been sent. Please check!.",
     *          "data": null,
     *      }
     *  }
     * @param  ResendVerificationEmailRequest  $emailRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendVerificationEmail(ResendVerificationEmailRequest $emailRequest)
    {
        $user= $this->service->findByEmail($emailRequest->email);
        if ($user->is_email_verified)
            return $this->respondWithSuccess(trans("messages.verification.email_already_verified"));


        $user->userVerifications()->delete();
        $this->service->sendVerificationEmail($user);
        return $this->respondWithSuccess(trans("messages.verification.email_sent"));

    }

    /**
     * Resend Verification Email To User
     *
     * @bodyParam user_id int required Example:5
     *
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "data": {
     *          "message": "An Email has been sent to your email. Please check!.",
     *          "data": null,
     *      }
     *  }
     * @param  ResendVerificationEmailToUserRequest  $verificationEmailRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmailToUser(ResendVerificationEmailToUserRequest $verificationEmailRequest)
    {
        $user= $this->service->findOrFail($verificationEmailRequest->user_id);
        if ($user->is_email_verified)
            return $this->respondWithSuccess(trans("messages.verification.email_already_verified"));


        $user->userVerifications()->delete();
        $this->service->sendVerificationEmail($user);
        return $this->respondWithSuccess(trans("messages.verification.email_sent"));

    }
}
