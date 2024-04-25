<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

/**
 * @group Permissions
 * @authenticated
 */
class GeneralController extends Controller
{
    use ResponseTrait;

    /**
     * List Roles
     *
     * @apiResourceModel \Spatie\Permission\Models\Role
     * @response {
     *    "data": [
     *        {
     *            "id": 1,
     *            "name": "admin",
     *            "display_name": "Admin",
     *            "permissions": [],
     *        }
     *    ]
     *}
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::all();

        return $this->respond([
            'data' => RoleResource::collection($roles)->additional([])
        ]);
    }
}
