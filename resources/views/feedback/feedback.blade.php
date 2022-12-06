<form action="{{ asset('feedback') }}" method="POST">
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Prepared by</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="prepared_by" class="form-control" value="{{ $name }}" readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Subject</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="subject" class="form-control"></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Tel no.</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="telno" class="form-control"></td>
            </tr>
            <tr>
                <td class=""><label>Message</label></td>
                <td>:</td>
                <td><textarea class="form-control" name="message" rows="10" style="resize:none;"></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>