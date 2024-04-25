
{{dump( $errors)}}
<form action="{{ URL::to('users/updatepassword') }}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <div class="form-group">
        <label for="name">Current Password</label>
        {!! $errors->first('old_password', '<p class="help-block" style="color: red">:message</p>') !!}
        <input type="password" name="old_password" class="form-control" id="old_password">
    </div>
    <div class="form-group">
        <label for="name">Password</label>
        <input type="password" name="password" class="form-control" id="password">
        {!! $errors->first('password', '<p class="help-block" style="color: red">:message</p>') !!}
    </div>
    <div class="form-group">
        <label for="name">New Password</label>
        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
        {!! $errors->first('password_confirmation', '<p class="help-block" style="color: red">:message</p>') !!}
    </div>

    <button type="submit" class="btn btn-primary">Change Password</button>
</form>