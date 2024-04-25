<?php

namespace App\Http\Controllers\Api\Users;

use App\Events\ChangeEmailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\ChangeRequestResource;
use App\Http\Resources\User\ProfileResource;
use App\Http\Resources\User\UserResource;
use App\Models\UserVerify;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use App\Enums\UserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Users
 * @authenticated
 */
class UserProfileController extends Controller
{
    use ResponseTrait;

    /**
     * Instance from lookup service.
     *
     * @var \App\Services\UserService
     */
    protected UserService $userService;

    /**
     * @param  \App\Services\UserService  $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Profile
     *
     * @responseFile storage/responses/profile/view.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $currentUser = me();

        return $this->respondWithSuccess(
            trans('messages.model.retrieve', ['model' => 'User']),
            new ProfileResource($currentUser)
        );
    }

    /**
     * Update Profile
     *
     * @responseFile storage/responses/profile/edit.json
     * @bodyParam name string required Example:john
     * @bodyParam email string required Example:john@test.com
     * @bodyParam profile_picture file
     * @bodyParam role_id int required Example:1
     * @param  UpdateUserRequest  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(UpdateUserRequest $request)
    {
        $data = $request->validated();

        if (data_get($data, 'email') != me()->email) {
            if (me()->socialAccounts->count() > 0) {
                return $this->setStatusCode(400)
                    ->respondWithSuccess(
                        trans("messages.user.change_email_banned")
                    );
            }
            $referrer= $request->headers->get('referer');
            event(
                new ChangeEmailEvent(me(), data_get($data, 'email'), $referrer)
            );

            unset($data['email']);
        }

        $user = $this->userService->update(me()->id, $data);

        return $this->respondWithSuccess(
            trans('messages.model.update', ['model' => 'User']),
            new ProfileResource($user)
        );
    }

    /**
     * Resend Change Email Request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function resendEmailChangeRequest(Request $request, UserVerify $changeRequest)
    {
        $referrer= $request->headers->get('referer');
        $userVerify= $this->userService->sendChangeEmailRequest(Auth::user(), $changeRequest->draft_email, $referrer);
        return $this->respondWithSuccess(
            trans('messages.user.change_email_request_resent',['newEmail'=>$userVerify->draft_email]),
            new ChangeRequestResource($userVerify)
        );
    }


    public function cancelEmailChangeRequests(Request $request)
    {
        $this->userService->deleteChangeEmailRequests(Auth::user());
        return $this->respondWithSuccess(
            trans('messages.user.change_email_request_cancelled'),
        );
    }

}
