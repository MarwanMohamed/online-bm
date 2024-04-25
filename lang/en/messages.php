<?php

return [
    'user'=>[
        'store'=>'User has been created successfully',
        'notStored'=>'Problem, Can not create user,Try again',
        'notFound'=>'Sorry, User Not found',
        'updated'=>'User has been updated successfully',
        'notUpdated'=>'Problem, Can not update user',
        'deleted'=>'User has been deleted successfully',
        'notDeleted'=>'Problem, Can not delete user',
        'checkPassword'=>'The current password is not correct, kindly enter correct data',
        'passwordNotMatch'=>'New password does not match the confirm password',
        'passwordChange'=>'Password has been changed successfully',
        'passwordNoChange'=>'Sorry, Can not change password, Try again',
        'mailNotSend'=>'Problem in sending mail, Try again',
        'social_login_success'=>'Social Log In Successfully',
        "change_email_banned"=>"You can't change your Email!. You're currently connected with one or more social account.",
        "change_email_request_exists"=>"Check the [   :newEmail   ] mailbox to verify your new email address. Your current email address will be used until the new address is verified.",
        "change_email_request_resent"=>"Change request has been sent!. Check the [   :newEmail   ] mailbox to verify your new email address.",
        "change_email_request_cancelled"=>"Change request has been cancelled!.",
    ],

    'login.success' => 'You have logged in successfully',
    'login.invalid' => 'The email/password you have entered is incorrect',
    'logout.success' => 'You have logged out successfully',
    'pictureDeleted' => 'Picture was removed successfully',
    'pictureNotFound' => 'You don\'t have profile picture',

    'model.store' => ':model have created successfully',
    'model.update' => ':model updated successfully',
    'model.retrieve' => ':model retrieved successfully',
    'model.list' => ':model listed successfully',
    'model.destroy' => ':model deleted successfully',
    'model.active' => ':model has been activated successfully',
    'model.inactive' => ':model has been deactivated successfully',
    'emails' => [
        'welcome' => [
            'subject' => 'Welcome to :app',
            'line1' => 'Hello :notifiable,',
            'line2' => 'Welcome to :app You’ve been added to the system',
            'line3' => 'You can login into the system using your email & the following data:',
            'line3_1' => 'You can use your email address & the following password to log in.',
            'line4' => 'Password: **:password**',
            'line4_1' => 'Your password is:  **:password**    [Feel free to change it if you want]',
            'action' => 'Go To Login Page',
            'footer' => 'Thank You! Best Regards,',
        ]
    ],

    'reset_password.email_not_verified' => "You're almost there - You've used unverified email address, Please verify it first to be able to login!.",
    'verification.email_changed' => "Your Email Has Been Changed Successfully!.",
    'verification.email_sent' => "An Email has been sent.",
    'verification.email_not_verified' => "You need to confirm your account. We have sent you an activation code, please check your email.",
    'verification.email_link_expired' => 'Sorry!. Link expired, Send Another Verification Email.',
    'verification.email_not_valid' => 'Sorry!. Verification Link Is Expired Or Used Before.',
    'verification.email_verified' => 'Great!. Your e-mail is now verified.',
    'verification.email_already_verified' => 'Your e-mail is already verified. You can now login',


    'roles.cantAssignRoleToDeletedRole'=>"Can't Assign Users To The Role That Will Be Deleted!.",
    'permissions.mandatory_permissions'=>":mandatory_permission is needed and must be added when assigning :permission",

];
