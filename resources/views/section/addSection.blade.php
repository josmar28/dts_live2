<form action="{{ asset('addSection') }}" method="POST" id="form">
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Code</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="text" name="code" id="code" class="form-control" required>
                </td>
            </tr>
            <tr>
                <td class=""><label>Division</label></td>
                <td>:</td>
                <td>
                    <div id="divisionBorder">
                    <select name="division" id="division" class="chosen-select">
                        <option value="">Select Division</option>
                        @foreach($division as $div)
                            <option value="{{ $div['id'] }}">{{ $div['description'] }}</option>
                        @endforeach
                    </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Description</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="text" name="description" id="description" class="form-control" onkeyup="checkDescription(this);" data-link="{{ asset('checkSection') }}" required>
                    <span class="hidden" style="color:red;">Description already used.</span>
                </td>
            </tr>
            <tr>
                <td class=""><label>Head</label></td>
                <td>:</td>
                <td>
                    <div id="headBorder">
                    <select name="head" id="head" class="chosen-select" required>
                        <option value="">Select Head</option>
                        @foreach($user as $head)
                            <option value="{{ $head['id'] }}">{{ $head['fname'].' '.$head['mname'].' '.$head['lname'] }}</option>
                        @endforeach
                    </select>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" onclick="divisionValidate();" id="sectionSubmit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
<script>
    $('.chosen-select').chosen();
</script>

