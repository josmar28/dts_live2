<form action="{{ asset('/form/office-order') }}" method="POST" id="form_route">
    {{ csrf_field() }}
    <input type="hidden" name="doctype" value="OFFICE_ORDER" />
    <div class="modal-body">
        <table>
            <tr>
                <td class="col-md-1"><img height="130" width="130" src="{{ asset('resources/img/ro7.png') }}" /></td>
                <td class="col-lg-10" style="text-align: center;">
                    Repulic of the Philippines <br />
                    <strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO. VII</strong><br />
                    Osmeña Boulevard, Cebu City, 6000 Philippines <br />
                    Regional Director's Office Tel. No. (032) 253-635-6355 Fax No. (032) 254-0109 <br />
                    Official Website:<a target="_blank" href="http://www.ro7.doh.gov.ph"><u>http://www.ro7.doh.gov.ph</u></a> Email Address: dohro7{{ '@' }}gmail.com
                </td>
                <td class="col-md-10"><img height="130" width="130" src="{{ asset('resources/img/ro7.png') }}" /> </td>
            </tr>
        </table>
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Prepared by</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="prepared_date" class="form-control" value="{{ $user }}" required readonly></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Prepared date</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><strong>{{ date('M') ." " .date('d') .", ". date('Y') }}</strong></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Subject</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="subject" class="form-control" required /></td>
            </tr>
            <tr>
                <td class=""><label>Message / Remarks</label></td>
                <td>:</td>
                <td><textarea class="form-control" name="description" rows="10" style="resize:none;" required></textarea></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>To be approve by:</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="approve_by" class="form-control" required /></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Designation</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="designation" class="form-control" required /></td>
            </tr>
        </table>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
<style>
    .underline {
        border-bottom: 1px solid #000000;
        width: 50px;
    }
</style>