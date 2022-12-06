


<form action="{{ asset('/form/office-order') }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="doctype" value="OFFICE_ORDER" />
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Prepared by</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="prepared_by" class="form-control" value="{{ $user }}" readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Prepared date</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="prepared_date" class="form-control" required readonly value="{{ date('Y-m-d H:i:s') }}"></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>From</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="routed_from" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Routed To</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="routed_to" class="form-control" required></td>
            </tr>

            <tr>
                <td class=""><label>Remarks/Description</label></td>
                <td>:</td>
                <td><textarea class="form-control" name="description" required rows="10" style="resize:none;"></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>