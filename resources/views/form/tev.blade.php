<form action="{{ asset('form/tev') }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
    <input type="hidden" value="{{ date('Y-m-d H:i:s') }}" name="prepared_date">
    <input type="hidden" value="TEV" name="doc_type">
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
                <td><textarea class="form-control" rows="10" style="resize:none;" name="description" required></textarea></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success" onclick="$('form').attr('target','');"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>

{{--<table class="table table-bordered">--}}
    {{--<tr>--}}
        {{--<td class="text-center" colspan="4">--}}
            {{--Republic of the Philippines<br>--}}
            {{--<strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO.VII</strong><br>--}}
            {{--Osmeña Boulevard, Cebu City, 6000 Philippines<br>--}}
            {{--Regional Director's Office Tel. No (032) 253-6355 Fax No. (032) 254-0109<br>--}}
            {{--Official Website <a href="http://www.dohro7.gov.ph">www.dohro7.gov.ph</a> / Email Address dohro7@gmail.com--}}
        {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td class="text-center" colspan="4">--}}
            {{--<strong>DISBURSEMENT VOUCHER</strong>--}}
        {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td class="col-sm-9 text-center" colspan="3">--}}
            {{--<strong>MODE OF PAYMENT</strong><br>--}}
            {{--<label class="col-sm-3">--}}
                {{--<input type="radio" name="payment"> MDS Check--}}
            {{--</label>--}}
            {{--<label class="col-sm-3">--}}
                {{--<input type="radio" name="payment"> Commercial Check--}}
            {{--</label>--}}
            {{--<label class="col-sm-3">--}}
                {{--<input type="radio" name="payment"> ADA--}}
            {{--</label>--}}
            {{--<label class="col-sm-3">--}}
                {{--<input type="radio" name="payment"> Other--}}
            {{--</label>--}}
        {{--</td>--}}
        {{--<td class="col-sm-3">--}}
            {{--No.:<br>--}}
            {{--Date:--}}
        {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td class="col-sm-6" rowspan="2" colspan="2">--}}
            {{--Payee / Office:<br>--}}
            {{--<input type="text" class="form-control" name="payee">--}}
        {{--</td>--}}
        {{--<td class="col-sm-3" rowspan="2">--}}
            {{--TIN/Employee No.:<br>--}}
            {{--<input type="text" name="tin" class="form-control">--}}
        {{--</td>--}}
        {{--<td class="col-sm-3">OS/BUS No.:</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td>Date:</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td class="col-sm-6" rowspan="2" colspan="2">--}}
            {{--Address:<br>--}}
            {{--<textarea name="address" class="col-sm-12"></textarea>--}}
        {{--</td>--}}
        {{--<td class="col-sm-3" colspan="2">--}}
            {{--Responsibility Center--}}
        {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td>Title:</td>--}}
    {{--</tr>--}}
{{--</table>--}}