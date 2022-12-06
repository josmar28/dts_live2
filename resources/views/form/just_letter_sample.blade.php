


<form action="{{ asset('/form/justification/letter') }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="doctype" value="JUST_LETTER" />
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Prepared by</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="prepared_by" class="form-control" value="{{ $name }}" readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>From</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="routed_from" class="form-control"></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Routed To</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="routed_to" class="form-control"></td>
            </tr>
            <tr>
                <td class=""><label>Remarks/Description</label></td>
                <td>:</td>
                <td><textarea class="form-control" name="description" rows="10" style="resize:none;"></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>