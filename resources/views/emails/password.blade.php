<!DOCTYPE html>
<html>
<head>
</head>

<body style="font-family: Arial; font-size: 12px;">
<div>
    <p>Hi {{$name}} . </p>
    <p>
        You have requested a password reset, please follow the link below to reset your password.
    </p>
    <p>
        Please ignore this email if you did not request a password change.
    </p>

    <p style="font-family: Lato, sans-serif; font-weight: 300; font-size: 14px; line-height: 22px; color: rgb(118, 119, 129); -webkit-font-smoothing: antialiased; text-align: left; margin: 0px !important;">
        <a href="{{url('users/resetPassword',$data['token'])}}">

            Follow this link to reset your password.
        </a>
    </p>


</div>
</body>
</html>