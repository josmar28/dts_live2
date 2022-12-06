<form action="{{ asset('prCashAdvance') }}" method="POST">
    <input type="hidden" name="doc_type" value="PRC">
    <input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class=""><label>Prepared Date</label></td>
                <td>:</td>
                <td><input name="prepared_date" value="{{ date('Y-m-d H:i:s') }}" class="form-control" readonly></td>
            </tr>
            <tr>
                <td class=""><label>Prepared By</label></td>
                <td>:</td>
                <td><input type="text" value="{{ Session::get('auth')->fname }} {{ Session::get('auth')->mname }} {{ Session::get('auth')->lname }}" class="form-control" disabled></td>
            </tr>
            <tr>
                <td class=""><label>Item Description</label></td>
                <td>:</td>
                <td><textarea class="form-control" id="description" name="description" rows="10" style="resize:none;" required></textarea></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Amount</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="number" id="amount" name="amount" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Requested By</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="requested_by" name="requested_by" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Charge to</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="charge_to" name="charge_to" class="form-control" required></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" onclick="PRC_reload();" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>