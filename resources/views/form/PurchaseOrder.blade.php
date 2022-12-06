<form action="{{ asset('PurchaseOrder') }}" method="POST">
    <input type="hidden" name="doc_type" value="PO">
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
                <td class="col-sm-3"><label>PO #</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="po_no" name="po_no" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Date</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control datepickercalendar" id="po_date" name="po_date" value="" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>PR #</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="pr_no" name="pr_no" class="form-control" required></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Date</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input id="pr_date" name="pr_date" class="form-control datepickercalendar" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Supplier</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" id="supplier" name="supplier" class="form-control" required></td>
            </tr>
            <tr>
                <td class=""><label>Addtional Info</label></td>
                <td>:</td>
                <td><textarea class="form-control" id="additional_info" name="additional_info" rows="10" style="resize:none;"></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" onclick="PO_reload();" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>