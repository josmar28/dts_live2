<?php
use App\User;
use App\Http\Controllers\DocumentController as Doc;
use App\Http\Controllers\AccessController as Access;

$access = Access::access();
$user = User::find(Session::get('auth')->id);

?>
<style>
    table tr td:first-child {
        font-weight:bold;
        color: #2b542c;
    }
    .daterangepicker {
        margin-top:-50px;
    }
</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<form action="{{ asset('document/create') }}" method="POST" class="form-submit">
    {{ csrf_field() }}
    <input type="hidden" name="doc_type" value="PRR_S" />
    <div class="modal-body">
        <div clas="row">
        <table class="table table-hover table-striped">
            <tr>
                <td class="text-right col-lg-4">Document Type :</td>
                <td class="col-lg-8">Purchase Request: Regular Order</td>
            </tr>
            <tr>
                <td class="text-right">Prepared By :</td>
                <td>{{ $user->fname.' '.$user->mname.' '.$user->lname }}</td>
            </tr>
            <tr>
                <td class="text-right">Prepared Date :</td>
                <td>{{ date('M d, Y h:i:s A') }}</td>
            </tr>
                <tr>
                    <td class="text-right">Remarks / Additional Information :</td>
                    <td>
                        <textarea name="description" class="typeahead form-control" rows="10" style="resize: vertical;"></textarea>
                        <div id="countryList">
                          </div>
                    </td>

                </tr>

                <tr>
                    <td class="text-right">Amount :</td>
                    <td>
                        <input type="text" name="amount" class="form-control"/>
                    </td>

                <tr>
                    <td class="text-right">Purpose:</td>
                    <td>
                        <input type="text" name="purpose" class="form-control"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-right">Source of Fund / Charge To :</td>
                    <td>
                        <input type="text" name="source_fund" class="form-control"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-right">Requested By :</td>
                    <td>
                        <input type="text" name="requested_by" class="form-control"/>
                    </td>
                </tr>

            
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>

<script>

});

    $('.daterange').daterangepicker({
        orientation: "auto"
    });

    $('.form-submit').on('submit',function(){
        $('.btn-submit').attr("disabled", true);
    });
</script>