<?php

namespace App\Home\Helpers;
use Gravatar;

class UserHelper
{
	public static function getProfilePicture($request)
	{
        $profilePicture = Gravatar::exists($request->email);

        if(isset($request->profile_picture) && $request->profile_picture->isValid())
        {
            $profilePicture = $request->profile_picture;
        }
        elseif(!isset($request->profile_picture) && $profilePicture)
        {
            $profilePicture = Gravatar::get($request->email);
        }
        else
        {
            $profilePicture = null;
        }
        
        return $profilePicture;
	}
}