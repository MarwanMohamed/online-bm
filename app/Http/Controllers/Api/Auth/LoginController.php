<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AuthService;

/**
 * @group Auth
 */
class LoginController extends Controller
{
    use ResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware("IsEmailVerified")->only(['login',"createTestingToken"]);
    }

    /**
     * Login
     *
     * To authenticate your SPA,
     * your SPA's "login" page should first make a request to the `/sanctum/csrf-cookie` endpoint
     * to initialize CSRF protection for the application.
     *
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "data": {
     *          "message": "You have logged in successfully",
     *          "data": null,
     *          "status_code": 200,
     *      }
     *  }
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $this->authService->authenticate($credentials);

        return $this->respondWithSuccess(
            trans('messages.login.success'),
        );
    }

    /**
     * Logout
     *
     * @response {
     *      "data": {
     *          "message": "You have logged out successfully",
     *          "data": null,
     *          "status_code": 200,
     *      }
     *  }
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->respondWithSuccess(trans('messages.logout.success'));
    }

    /**
     * Create Testing Token
     *
     * @header Content-Type application/x-www-form-urlencoded
     * @response {
     *      "data": {
     *          "access_token": "<<ACCESS_TOKEN>>",
     *      }
     *  }
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @throws \Illuminate\Auth\AuthenticationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTestingToken(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $access_token = $this->authService->createTestingToken($credentials);
        
        return $this->respondWithSuccess(
            trans('messages.login.success'),
            ['access_token' => $access_token]
        );
    }
}
