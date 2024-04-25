<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\ISocialProvider;
use App\Exceptions\ProviderAuthorizationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SocialLoginRequest;
use App\Http\Resources\User\UserResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Auth
 */
class SocialLoginController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct(ISocialProvider $socialLoginService)
    {
        $this->service = $socialLoginService;
    }

    /**
     * Generate RedirectUrl to your different providers.
     *
     * @urlParam providerId string required Example:GOOGLE
     * @response {
     *      "message": "Redirect Link Generated Successfully"
     *      "data": {
     *          "redirectUrl": "{provider-redirect-url}"
     *      }
     *  }
     */
    public function getProviderUrl(Request $request)
    {
        $requestId = $request->query('requestId');
        $providerId = $request->input('providerId');

        $redirectUrl = $this->service->getAuthRedirectUrl();

        return $this->respondWithSuccess(
            trans('messages.socialLogin.redirectSuccess'),
            ['redirectUrl' => $redirectUrl]
        );
    }

    /**
     * Handle callback from your provider. add user to system and login if not logged in yet.
     *
     * @urlParam providerId string required Example:META
     * @queryParam userType string required Example:CLIENT/PARTNER
     * @queryParam code string required
     * @response {
    "message": "Social Log In Successfully",
    "data": {
    "id": 27,
    "name": "Khaled Gamal",
    "email": "khaled.gamal@test.com",
    "profile_picture": null,
    "is_active": true,
    "created_at": {
    "date": "19-06-2023",
    "datetime": "19-06-2023 06:59:22 AM",
    "for_humans": "27 seconds ago",
    "formatted": "Mon, Jun 19, 2023 6:59 AM"
    }
    }
    }
     */
    public function handleProviderCallback(Request $request)
    {
        try {
            $redirectUrl= config('services.google.redirect');
            $user = $this->service->getUser($redirectUrl);
            $dbUser= $this->service->handleLoginCallback($user);
            Auth::login($dbUser);
            return $this->respondWithSuccess(
                trans('messages.user.social_login_success'),
                new UserResource($dbUser)
            );
        }catch (ProviderAuthorizationException $authorizationException){
            return  $authorizationException->render($request);
        }catch (\Exception $exception){
            return  $this->setStatusCode(400)->respondWithSuccess($exception->getMessage());
        }
    }
}
