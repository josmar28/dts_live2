
<form action="" method="POST" id="create" data-link="{{ asset('check/user') }}">
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>First name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="fname" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Middle name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="mname" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Last name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="lname" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Username</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="text" name="username" class="form-control" required>
                    <span class="hidden" style="color:red;">User name already used.</span>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Password</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="password" name="password" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>User Type</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="user_type" required id="user_type" class="form-control">
                        <option value="" disabled selected>Select user type</option>
                        <option value="1">Admin</option>
                        <option value="0">Standard</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Designation</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="designation" required id="select_dis" class="chosen-select form-control" data-link="{{ asset('/get/section') }}">
                        <option value="" selected disabled>Select Designation</option>
                        @foreach($dis as $a)
                            <option value="{{ $a->id }}">{{ $a->description }}</option>
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
                        @foreach($div as $d)
                            <option value="{{ $d->id }}">{{ $d->description }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tbody class="section_append">

            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success submit" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>Loading">Submit</button>
    </div>
</form>

<script>
    $('.chosen-select').chosen();
</script>
