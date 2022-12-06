
<form action="{{ asset('/form/application/leave') }}" method="POST" id="form_route">
    {{ csrf_field() }}
    <input type="hidden" name="doctype" value="APP_LEAVE" />
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
                <td class="col-sm-3"><label>Applicant name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="applicant_name" class="form-control" required></td>
            </tr> <tr>
                <td class="col-sm-3"><label>No. of days leave</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="days_leave" class="form-control" required></td>
            </tr>
            <tr>
                <td class=""><label>Date Range</label></td>
                <td>:</td>
                <td>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" id="reservation" name="daterange" value="" onkeyup="($(this).daterangepicker())">
                    </div>
                </td>
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
