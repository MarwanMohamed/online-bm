<?php

namespace App\Http\Controllers\Api\Users;

use App\Enums\Feature;
use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\MiniUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use YlsIdeas\FeatureFlags\Facades\Features;


/**
 * @group Users
 * @authenticated
 */
class UsersController extends Controller
{
    use ResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->middleware('permission:view_user')->only('index', 'show');
        $this->middleware('permission:add_user')->only('store');
        $this->middleware('permission:edit_user')->only('update');
        $this->middleware('permission:delete_user')->only('destroy');
        $this->middleware('permission:active_user')->only('toggleActive');
    }

    /**
     * List Users For Dropdowns
     *
     * This API does not need any permissions.
     *
     * @queryParam name Search users by name used for autocompletion. Example:admin
     * @apiResourceCollection \App\Http\Resources\User\MiniUserResource
     * @apiResourceModel \App\Models\User
     * @return mixed
     */
    public function listAll()
    {
        $users = User::query()->limit(20);

        if ($name = request('name')) {
            $users->where('name', 'like', "%$name%");
        }

        if (request()->has('is_active')) {
            $users->where('is_active', request()->boolean('is_active'));
        }

        return MiniUserResource::collection($users->get());
    }

    /**
     * List Users For Datatable
     *
     * This API needs `view_user` permissions.
     *
     * @queryParam selected_ids[] Display only users with the given ids. Example:1
     * @queryParam is_active boolean Display only active users when value is "1", "true", "on", and "yes". Otherwise, Display only inactive users. Example:1
     * @apiResourceCollection \App\Http\Resources\User\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional draw=0 recordsTotal=1 recordsFiltered=1
     * @param  \App\Http\Requests\DatatableRequest  $request
     * @return mixed
     */
    public function index(DatatableRequest $request)
    {
        $dataTables = $this->userService->datatable($request);

        return $dataTables->setTransformer(function ($item) {
            return UserResource::make($item)->resolve();
        })->toJson();
    }

    /**
     * Add User
     *
     * This API needs `add_user` permissions.
     *
     * @apiResource \App\Http\Resources\User\UserResource
     * @apiResourceModel \App\Models\User
     * @bodyParam name string required Example:john
     * @bodyParam email string required Example:john@test.com
     * @bodyParam password string required Example:password
     * @bodyParam password_confirmation string required Example:password
     * @bodyParam profile_picture file Allow Extensions: (jpeg, jpg, bmp, png, gif)
     * @bodyParam role_id int required Example:1
     * @apiResourceAdditional message="user have created successfully"
     * @param  StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        if (Features::accessible(Feature::AUTO_GENERATE_PASSWORD())){
            $validatedData['password'] = randomPassword();
        }

        $user = $this->userService->store($validatedData);
        $user= $this->userService->checkEmailVerification($user);

        $referrer= $request->headers->get('referer');
        event(new UserCreated($user, $validatedData['password'], $referrer));

        return $this->respondCreated(
            trans('messages.model.store', ['model' => 'User']),
            new UserResource($user)
        );
    }

    /**
     * Update User
     *
     * This API needs `edit_user` permissions.
     *
     * @urlParam id int required Example:1
     * @apiResource \App\Http\Resources\User\UserResource
     * @apiResourceModel \App\Models\User
     * @bodyParam name string required Example:john
     * @bodyParam email string required Example:john@test.com
     * @bodyParam password string required Example:password
     * @bodyParam password_confirmation string required Example:password
     * @bodyParam profile_picture file Allow Extensions: (jpeg, jpg, bmp, png, gif)
     * @bodyParam role_id int required Example:1
     * @apiResourceAdditional message="user have updated successfully"
     * @param  UpdateUserRequest  $request
     * @param User $user
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user = $this->userService->update($user->id, $validatedData);

        return $this->respondWithSuccess(
            trans('messages.model.update', ['model' => 'User']),
            new UserResource($user)
        );
    }

    /**
     * Show User
     *
     * This API needs `view_user` permissions.
     *
     * @urlParam id int required Example:1
     * @apiResource \App\Http\Resources\User\UserResource
     * @apiResourceModel \App\Models\User
     * @param  User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = $this->userService->findOrFail($user->id);

        return $this->respondWithSuccess(
            trans('messages.model.retrieve', ['model' => 'User']),
            new UserResource($user->loadRelations())
        );
    }

    /**
     * Delete User
     *
     * This API needs `delete_user` permissions.
     *
     * @urlParam id int required Example:1
     * @response {
     *      "data": {
     *          "message": "User deleted successfully",
     *          "data": null,
     *          "status_code": 200
     *      }
     *  }
     * @param  User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->userService->destroy($user->id);

        return $this->respondWithSuccess(
            trans('messages.model.destroy', ['model' => 'User'])
        );
    }

    /**
     * Active & Deactivate
     *
     * This API needs `active_user` permissions.
     *
     * @urlParam id int required Example:1
     * @apiResource \App\Http\Resources\User\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional message="user have been activated successfully"
     * @param  User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive(User $user)
    {
        $user = $this->userService->toggleActive($user->id);

        $message = trans('messages.model.active', ['model' => 'User']);

        if (! $user->is_active) {
            $message = trans('messages.model.inactive', ['model' => 'User']);
        }

        return $this->respondWithSuccess(
            $message,
            new UserResource($user->loadRelations())
        );
    }
}
