<?php

namespace App\Rules;

use App\Models\Permission;
use Illuminate\Contracts\Validation\Rule;

class CheckMandatryPermissionsPermission implements Rule
{
    protected $mandatoryPermissionsMessages;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mandatoryPermissionsMessages= [];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $permission= Permission::find($value);

        if ($permission->mandatory_permissions){
            $request= request();
            $mandatoryPermissionsMessages= Permission::whereIn('name', $permission->mandatory_permissions)
                ->whereNotIn('id',$request->permissions)
                ->get()
                ->map(function (Permission $perm) use ($permission){
                   return trans("messages.permissions.mandatory_permissions",['mandatory_permission'=>$perm->display_name, "permission"=>$permission->display_name]);
                });
            if ($mandatoryPermissionsMessages->count() > 0)
                $this->mandatoryPermissionsMessages= [$attribute=> $mandatoryPermissionsMessages->all()];
        }
        return count($this->mandatoryPermissionsMessages) == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->mandatoryPermissionsMessages;
    }
}
