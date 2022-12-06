<form action="{{ asset('form/salary') }}" method="POST">
{{ csrf_field() }}

    <input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
    <input type="hidden" value="{{ date('Y-m-d H:i:s') }}" name="prepared_date">
    <input type="hidden" value="SAL" name="doc_type">
    <div class="modal-body">                                                            
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Prepared By</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" disabled value="{{ Session::get('auth')->fname }} {{ Session::get('auth')->mname }} {{ Session::get('auth')->lname }}" class="form-control"></td>
            </tr>
            <tr>
                <td class=""><label>Prepared Date</label></td>
                <td>:</td>
                <td><input type="text" disabled value="{{ date('m/d/Y h:i:s A') }}"  class="form-control"></td>
            </tr>
            <tr>
                <td class=""><label>Amount</label></td>
                <td>:</td>
                <td><input type="text" name="amount" class="form-control" required onkeyup="acceptNumber($(this));"></td>
            </tr>
            <tr>
                <td class=""><label>Date Range</label></td>
                <td>:</td>
                <td>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" id="reservation" name="daterange" value="">
                    </div>
                </td>
            </tr>
            <tr>
                <td class=""><label>Additional Information</label></td>
                <td>:</td>
                <td><textarea class="form-control" rows="5" style="resize:none;" name="description" required></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success" onclick="$('form').attr('taraget','');"><i class="fa fa-send"></i> Submit</button>
    </div>
 </form>

