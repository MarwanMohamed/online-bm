<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\Role\DeleteRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleResource;
use App\Services\RoleService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Roles
 * @authenticated
 */
class RoleController extends Controller
{
    use ResponseTrait;

    protected $service;

    public function __construct(RoleService $roleService)
    {
        $this->service= $roleService;
    }

    /**
     * List Roles For Dropdowns
     *
     * This API does not need any permissions.
     *
     * @apiResourceCollection \App\Http\Resources\Role\RoleResource
     * @apiResourceModel \App\Models\Role 
     * @return mixed
     */
    public function listAll()
    {
        $roles = Role::query();
        return RoleResource::collection($roles->get());
    }

    /**
     * List Roles For Datatable
     *
     * This API needs `view_role` permissions.
     *
     * @apiResourceCollection \App\Http\Resources\Role\RoleResource
     * @apiResourceModel \App\Models\Role 
     * @apiResourceAdditional draw=0 recordsTotal=1 recordsFiltered=1
     * @param  \App\Http\Requests\DatatableRequest  $request
     * @return mixed
     */
    public function index(DatatableRequest $request)
    {
        $dataTables = $this->service->datatable($request);

        return $dataTables->setTransformer(function ($item) {
            return RoleResource::make($item)->resolve();
        })->toJson();
    }

    /**
     * Add Role
     *
     * This API needs `add_role` permissions.
     *
     * @apiResource \App\Http\Resources\Role\RoleResource
     * @apiResourceModel \App\Models\Role
     * @bodyParam title string required Example:HR
     * @bodyParam description string required Example:Handling All Employee Stuff And Account Management
     * @bodyParam permissions array required
     * @bodyParam permissions.* int required Example:2
     * @apiResourceAdditional message="role have created successfully"
     * @param  StoreRoleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = $this->service->store($validatedData);

        return $this->respondCreated(
            trans('messages.model.store', ['model' => 'Role']),
            new RoleResource($role)
        );
    }

    /**
     * Update Role
     *
     * This API needs `edit_role` permissions.
     *
     * @urlParam id int required Example:1
     * @apiResource \App\Http\Resources\Role\RoleResource
     * @apiResourceModel \App\Models\Role 
     * @bodyParam title string required Example:HR
     * @bodyParam description string required Example:Handling All Employee Stuff And Account Management
     * @bodyParam permissions array required
     * @bodyParam permissions.* int required Example:2
     * @apiResourceAdditional message="role have updated successfully"
     * @param  UpdateRoleRequest  $request
     * @param Role $role
     * @return mixed
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();

        $role = $this->service->update($role->id, $validatedData);

        return $this->respondWithSuccess(
            trans('messages.model.update', ['model' => 'Role']),
            new RoleResource($role)
        );
    }

    /**
     * Show Role
     *
     * This API needs `view_role` permissions.
     *
     * @urlParam id int required Example:1
     * @apiResource \App\Http\Resources\Role\RoleResource
     * @apiResourceModel \App\Models\Role 
     * @param  Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        $role = $this->service->findOrFail($role->id);

        return $this->respondWithSuccess(
            trans('messages.model.retrieve', ['model' => 'Role']),
            new RoleResource($role->loadRelations())
        );
    }

    /**
     * Delete Role
     *
     * This API needs `delete_role` permissions.
     *
     * @urlParam id int required Example:1
     * @response {
     *      "data": {
     *          "message": "Role deleted successfully",
     *          "data": null,
     *          "status_code": 200
     *      }
     *  }
     * @param  Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRoleRequest $request ,Role $role)
    {
        $this->service->assignUsersRoles($role, $request->new_role);
        $this->service->destroy($role->id);

        return $this->respondWithSuccess(
            trans('messages.model.destroy', ['model' => 'Role'])
        );
    }


}
