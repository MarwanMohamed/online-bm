<?php

namespace App\Http\Controllers\Api\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Permissions\PermissionsListResource;
use App\Models\Permission;
use App\Traits\ResponseTrait;

/**
 * @group Permissions
 * @authenticated
 */
class PermissionController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware(['role:admin'],['only' => ['list']]);
    }

    /**
     * List System Permissions
     *
     * @responseFile storage/responses/permissions/list.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $allPermissions = Permission::all();
        return $this->respondWithSuccess(
            trans('messages.model.retrieve', ['model' => 'Permissions']),
            PermissionsListResource::collection($allPermissions)
        );
    }
}
