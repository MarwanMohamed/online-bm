<?php

namespace App\Repositories;


use App\Models\Role;

class RoleRepository extends BaseRepository
{
    protected $model = Role::class;

    public function store(array $data = [])
    {
        $role= Role::create([
            'display_name'=>$data['title'],
            'name'=>strtolower($data['title']),
            'description'=>strtolower($data['description']),
            'guard_name'=>'web',
        ]);
        return $role;
    }

    public function update(int $id, array $data)
    {
        $role= $this->findOrFail($id);
        $role->update([
            'display_name'=>$data['title'],
            'name'=>strtolower($data['title']),
            'description'=>strtolower($data['description']),
            'guard_name'=>'web',
        ]);
        $role->permissions()->sync($data['permissions']);
        return $role->refresh();
    }


    public function destroy($id)
    {
        $role= $this->find($id);
        $role->permissions()->detach();
        parent::destroy($id);
    }


}
