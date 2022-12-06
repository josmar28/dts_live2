<form action="{{ asset('/form/worksheet') }}" method="POST" id="form_route">
    {{ csrf_field() }}
    <input type="hidden" name="doctype" value="WORKSHEET" />
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Prepared by</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="routed_from" class="form-control" value="{{ $user }}" required readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Prepared date</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="prepared_date" class="form-control" required readonly value="{{ date('Y-m-d H:i:s') }}"></td>
            </tr>
            <tr>
                <td class=""><label>Additional Information/Remarks</label></td>
                <td>:</td>
                <td><textarea class="form-control" name="description" rows="10" style="resize:none;" required></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
