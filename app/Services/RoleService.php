<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\RoleRepository;

class RoleService extends BaseService
{
    protected $repository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->repository = $roleRepository;
    }

    public function assignUsersRoles($currentRole, $newRoleId)
    {
        $users= $currentRole->users->pluck('id')->toArray();
        $currentRole->users()->detach();

        $newRole= $this->findOrFail($newRoleId);
        $newRole->users()->syncWithoutDetaching($users);
    }
}
