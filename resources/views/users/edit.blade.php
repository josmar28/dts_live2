
<form action="" method="POST" id="create">
    {{ csrf_field() }}
    
    <input type="hidden" name="id" value="{{ $user->id }}" />
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>First name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="fname" value="{{ $user->fname }}" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Middle name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="mname" value="{{ $user->mname }}" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Last name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="lname" value="{{ $user->lname }}" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Username</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="text" name="username" value="{{ $user->username }}" class="form-control" onblur="checkUser(this);" data-link="{{ asset('check/user') }}"required>
                </td>
            </tr>
            <tr>
                 <td class="col-sm-3"><label>Reset Password</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="text" name="reset_pass" class="form-control" placeholoder="Password Unchanged" >
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>User Type</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="user_type" required id="user_type" class="form-control">
                        <option value="" disabled selected>Select user type</option>
                        <option {{ ($user->user_priv == 1 ? 'selected' : '') }} value="1">Admin</option>
                        <option {{ ($user->user_priv == 0 ? 'selected' : '') }} value="0">Standard</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Designation</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="designation" required id="select_dis" class="chosen-select form-control" data-link="{{ asset('/get/section') }}">
                        <option value="" selected disabled>Select Designation</option>
                        @foreach($designation as $a)
                            <option {{ ($user->designation == $a->id ? 'selected' : '') }} value="{{ $a->id }}">{{ $a->description }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Division</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="division" required id="select_div" onchange="loadDivision(this);" class="chosen-select form-control" data-link="{{ asset('/get/section') }}">
                        <option value="" selected disabled>Select division</option>
                        @foreach($division as $d)
                            <option {{ ($user->division == $d->id ? 'selected' : '') }} value="{{ $d->id }}">{{ $d->description }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Section</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="section" required id="select_div" onchange="loadDivision(this);" class="chosen-select form-control" data-link="{{ asset('/get/section') }}">
                        <option value="" selected disabled>Select section</option>
                        @foreach($section as $d)
                            <option {{ ($user->section == $d->id ? 'selected' : '') }} value="{{ $d->id }}">{{ $d->description }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success user_add" id="update_user" name="update" value="update"><i class="fa fa-send"></i>Update</button>
    </div>
</form>
<script>
    $('.chosen-select').chosen();
</script>
