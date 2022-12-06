<?php
use App\User;
use App\Http\Controllers\DocumentController as Doc;
use App\Http\Controllers\AccessController as Access;
use App\Tracking_Details;

$routed = Tracking_Details::where('route_no',$document->route_no)
            ->count();
$access = Access::access();
$user = User::find($document->prepared_by);
$filter = Doc::isIncluded($document->doc_type);
?>

@if(Session::get('auth')->id == $document->prepared_by) {{-- && $routed==1--}}
    <?php $status = ''; ?>
@else
    <?php $status = 'disabled'; ?>
@endif
<style>
    .table-info tr td:first-child {
        font-weight:bold;
        color: #2b542c;
    }
</style>
<form action="{{ asset('document/update') }}" method="post" class="form-submit">
{{ csrf_field() }}
<input type="hidden" name="currentID" value="{{ $document->id }}" />
<table class="table table-hover table-striped table-info">

    <tr>
                <td class="text-right col-lg-4">Document Type :</td>
                <td class="col-lg-8">
            <select id="doc_type" name="doc_type" class="form-control" required>
           <option value="">Select Document Type</option>
           <?php
            $doc_types = App\Tracking_Filter::where('doc_type', '!=' , 'GENERAL')
            ->where('doc_type', '!=' , 'PRC')
            ->where('doc_type', '!=' , 'PRR_M')
            ->orderby('doc_description','asc')
            ->get();
           ?>
             @foreach($doc_types as $row)
              <option {{ ($document->doc_type == $row->doc_type ? 'selected' : '') }} value="{{ $row->doc_type }}"> {{ $row->doc_description }}</option>
              @endforeach
         </select>
                </td>
     </tr>
    <tr>
        <td class="text-right">Prepared By :</td>
        <td>{{ $user->fname.' '.$user->mname.' '.$user->lname }}</td>
    </tr>
    <tr>
        <td class="text-right">Prepared Date :</td>
        <td>{{ date('M d, Y h:i:s A',strtotime($document->prepared_date)) }}</td>
    </tr>
    <tr class="{{ $filter[0] }}">
        <td class="text-right">Remarks :</td>
        <td>
            <?php $breaks = array("<br />","<br>","<br/>"); ?>
            <?php $desc = str_ireplace($breaks, "\r\n", $document->description);?>
            <textarea name="description" {{ $status }} class="form-control" rows="10" style="resize: vertical;">{!! ($desc) !!}</textarea>
        </td>
    </tr>
    <tr class="{{ $filter[1] }}">
        <td class="text-right">Amount :</td>
        <td>
            <input type="text" name="amount" class="form-control" value="{{ $document->amount }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[2] }}">
        <td class="text-right">PR # / BARCODE:</td>
        <td>
            <input type="text" name="pr_no" class="form-control" value="{{ $document->pr_no }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[2] }}">
        <td class="text-right">Date :</td>
        <td>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="date" name="pr_date" class="form-control" value="{{ date('Y-m-d',strtotime($document->pr_date)) }}" {{ $status }}>
            </div>
        </td>
    </tr>
    <tr class="{{ $filter[3] }}">
        <td class="text-right">PO # :</td>
        <td>
            <input type="text" name="po_no" class="form-control" value="{{ $document->po_no }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[3] }}">
        <td class="text-right">Date :</td>
        <td>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="date" name="po_date" class="form-control" value="{{ date('Y-m-d',strtotime($document->po_date)) }}" {{ $status }}>
            </div>
        </td>
    </tr>
    <tr class="{{ $filter[4] }}">
        <td class="text-right">Purpose:</td>
        <td>
            <input type="text" name="purpose" class="form-control" value="{{ $document->purpose }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[5] }}">
        <td class="text-right">Source of Fund / Charge To :</td>
        <td>
            <input type="text" name="source_fund" class="form-control" value="{{ $document->source_fund }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[6] }}">
        <td class="text-right">Requested By :</td>
        <td>
            <?php $user = \App\Users::find($document->requested_by);
            //print_r($document->requested_by);
            ?>
            @if($user)
             <input type="text" name="requested_by" class="form-control" value="{{ $user->lname }}, {{ $user->fname }}" disabled />
            @else <input type="text" name="requested_by" class="form-control" value="{{$document->requested_by}}" disabled />
            @endif
        </td>
    </tr>
    <tr class="{{ $filter[7] }}">
        <td class="text-right">Route To :</td>
        <td>
            <input type="text" name="route_to" class="form-control" value="{{ $document->route_to }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[8] }}">
        <td class="text-right">Route From :</td>
        <td>
            <input type="text" name="route_from" class="form-control" value="{{ $document->route_from }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[9] }}">
        <td class="text-right">Supplier :</td>
        <td>
            <input type="text" name="supplier" class="form-control" value="{{ $document->supplier }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[10] }}">
        <td class="text-right">Date of Event :</td>
        <td>
            <input type="date" name="event_date" class="form-control" value="{{ $document->event_date }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[11] }}">
        <td class="text-right">Location of Event :</td>
        <td>
            <input type="text" name="event_location" class="form-control" value="{{ $document->event_location }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[12] }}">
        <td class="text-right">Participants :</td>
        <td>
            <input type="text" name="event_participant" class="form-control" value="{{ $document->event_participant }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[13] }}">
        @if($filter[13]!='hide')
        <?php $applicant = User::find($document->cdo_applicant); ?>
        <td class="text-right">Applicant :</td>
        <td>
            <input type="text" name="cdo_applicant" class="form-control" value="{{ $document->cdo_applicant }}" {{ $status }} />
        </td>
        @endif
    </tr>
    <tr class="{{ $filter[14] }}">
        <td class="text-right">Number of Days :</td>
        <td>
            <input type="text" name="cdo_day" class="form-control" value="{{ $document->cdo_day }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[15] }}">
        <td class="text-right">Date Range :</td>
        <td>
            <input type="text" class="form-control daterange" name="event_daterange" value="{{ $document->event_daterange }}" {{ $status }}>
        </td>
    </tr>
    <tr class="{{ $filter[16] }}">
        <td class="text-right">Payee :</td>
        <td>
            <input type="text" name="payee" class="form-control" value="{{ $document->payee }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[17] }}">
        <td class="text-right">Item/s :</td>
        <td>
            <input type="text" name="item" class="form-control" value="{{ $document->item }}" {{ $status }} />
        </td>
    </tr>
    @if($access=='accounting')
    <tr class="{{ $filter[18] }}">
        <td class="text-right">DV Number :</td>
        <td>
            <input type="text" name="dv_no" class="form-control" value="{{ $document->dv_no }}" {{ $status }} />
        </td>
    </tr>
    @endif
    @if($access=='budget')
    <tr class="{{ $filter[19] }}">
        <td class="text-right">ORS Number :</td>
        <td>
            <input type="text" name="ors_no" class="form-control" value="{{ $document->ors_no }}" {{ $status }} />
        </td>
    </tr>
    <tr class="{{ $filter[20] }}">
        <td class="text-right">Fund Source :</td>
        <td>
            <input type="text" name="fund_source_budget" class="form-control" value="{{ $document->fund_source_budget }}" {{ $status }} />
        </td>
    </tr>
    @endif
</table>

<div class="modal-footer">
    @if(Session::get('doc_type') == 'PRR_M')
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <a href="{{ asset('prr_meal_page') }}" class="btn btn-warning"><i class="fa fa-barcode"></i> View Document</a>
    @else
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        @if(!$status)
            <button type="submit" class="btn btn-info" name="submit" value="update"><i class="fa fa-upload"></i> Update</button>
            @if($routed < 2)
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="modal" data-target="#deleteDocument"><i class="fa fa-trash"></i> Remove</button>
            @endif
        @endif
        <button type="button" class="btn btn-success selectPaperSize" data-dismiss="modal" data-toggle="modal" data-target="#paperSize"><i class="fa fa-barcode"></i> Barcode v1</button>
        <a target="_blank" href="{{ asset('pdf/track') }}" class="btn btn-success"><i class="fa fa-barcode"></i> Barcode v2</a>
    @endif
</div>
</form>

<script>
    $('.daterange').daterangepicker();
    function paperSizeAndOrientation(url){
        var paperOrientaion = $('input[name=paperOrientation]:checked').val();
        window.open(url+"/"+paperOrientaion, '_blank');
        event.preventDefault();
    }
</script>
