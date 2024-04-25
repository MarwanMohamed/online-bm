<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ChangeEmailCheck implements Rule
{
    protected $email;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->email= $value;
        $user= Auth::user();
        if ($user->emailChangeRequests->count() > 0 && $user->email !== $value){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("messages.user.change_email_request_exists", ["newEmail"=>$this->email]);
    }
}
