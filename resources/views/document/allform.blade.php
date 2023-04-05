<?php
use App\User;
use App\Http\Controllers\DocumentController as Doc;
use App\Http\Controllers\AccessController as Access;

$access = Access::access();
$user = User::find(Session::get('auth')->id);
$filter = Doc::isIncluded($doc_type);
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

<form action="{{ asset('document/create') }}" method="POST" class="form-submit" autocomplete="off">
    {{ csrf_field() }}
    <input type="hidden" name="doc_type" value="{{ $doc_type }}" />
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <div class="modal-body">
   
        <table class="table table-hover table-striped">
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
                <option {{ ($doc_type == $row->doc_type ? 'selected' : '') }} value="{{ $row->doc_type }}"> {{ $row->doc_description }}</option>
              @endforeach
         </select>
                </td>
            </tr>
            <!-- <tr style="visibility: hidden">
                <td class="text-right col-lg-4">Document Type :</td>
                <td class="col-lg-8">{{ Doc::docTypeName($doc_type) }}</td>
            </tr> -->
            <tr>
                <td class="text-right">Prepared By :</td>
                <td>{{ $user->fname.' '.$user->mname.' '.$user->lname }}</td>
            </tr>
            <tr>
                <td class="text-right">Prepared Date :</td>
                <td>{{ date('M d, Y h:i:s A') }}</td>
            </tr>
            @if($filter[0]!='hide')
                <tr>
                    <td class="text-right">Remarks / Additional Information :</td>
                    <td>
                        <textarea name="description" class="typeahead form-control" rows="10" style="resize: vertical;" required></textarea>
                        <div id="countryList">
                          </div>
                    </td>
                    {{ csrf_field() }}
                </tr>
            @endif
            @if($filter[15]!='hide')
                <tr>
                    <td class="text-right">Date Range :</td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control daterange" name="event_daterange">
                        </div>
                    </td>
                </tr>
            @endif
            @if($filter[1]!='hide')
                <tr>
                    <td class="text-right">Amount :</td>
                    <td>
                        <input type="text" name="amount" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[2]!='hide')
            <?php
                $barcode = DB::connection('prdb')->table('procure_main')->pluck('id');
                // print_r($barcode);
            ?>
                <tr>
                    <td class="text-right">BARCODE :</td>
                    <td>
                        <!-- <input type="text" name="pr_no" class="form-control" required/> -->
                        <input list="pr_no" name="pr_no" class="form-control pr_no" required>
                        <datalist id = "pr_no">
                        @foreach($barcode as $dataa)
                                <option value="{{ $dataa }}" >{{ $dataa }}</option>
                            @endforeach
                        </datalist>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Date :</td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" name="pr_date" class="form-control" required>
                        </div>
                    </td>
                </tr>
            @endif
            @if($filter[3]!='hide')
                <tr>
                    <td class="text-right">PO # :</td>
                    <td>
                        <input type="text" name="po_no" class="form-control" required/>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Date :</td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" name="po_date" class="form-control" required>
                        </div>
                    </td>
                </tr>
            @endif
            @if($filter[4]!='hide')
                <tr>
                    <td class="text-right">Purpose:</td>
                    <td>
                        <input type="text" name="purpose" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[5]!='hide')
                <tr>
                    <td class="text-right">Source of Fund / Charge To :</td>
                    <td>
                        <input type="text" name="source_fund" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[6]!='hide')
                <tr>
                    <td class="text-right">Requested By :</td>
                    <td>
                        <input type="text" name="requested_by" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[7]!='hide')
                <tr>
                    <td class="text-right">Route To :</td>
                    <td>
                        <input type="text" name="route_to" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[8]!='hide')
                <tr>
                    <td class="text-right">Route From :</td>
                    <td>
                        <input type="text" name="route_from" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[9]!='hide')
                <tr>
                    <td class="text-right">Supplier :</td>
                    <td>
                        <input type="text" name="supplier" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[10]!='hide')
                <tr>
                    <td class="text-right">Date of Event :</td>
                    <td>
                        <input type="date" name="event_date" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[11]!='hide')
                <tr>
                    <td class="text-right">Location of Event :</td>
                    <td>
                        <input type="text" name="event_location" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[12]!='hide')
                <tr>
                    <td class="text-right">Participants :</td>
                    <td>
                        <input type="text" name="event_participant" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[13]!='hide')
                <tr>
                    <td class="text-right">Applicant :</td>
                    <td>
                        <input type="text" name="cdo_applicant" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[14]!='hide')
                <tr>
                    <td class="text-right">Number of Days :</td>
                    <td>
                        <input type="text" name="cdo_day" class="form-control"/>
                    </td>
                </tr>
            @endif
            @if($filter[16]!='hide')
                <tr>
                    <td class="text-right">Payee :</td>
                    <td>
                        <input type="text" name="payee" class="form-control" />
                    </td>
                </tr>
            @endif
            @if($filter[17]!='hide')
                <tr>
                    <td class="text-right">Item/s :</td>
                    <td>
                        <input type="text" name="item" class="form-control"  />
                    </td>
                </tr>
            @endif
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
@section('plugin_old')
<script>
$('.pr_no').on('change', function() {
    $('.loading').show();
    <?php echo 'var url ="'.asset('check/PRno').'";';?>
    var json = {
            "pr_no" : $(this).val(),
            "_token" : "<?php echo csrf_token(); ?>"
        };
        $.post(url,json,function(result){
             $('.loading').hide();
            if(result == 1 )
            {
                alert('PR number is already exist! Try another one');
                $('.pr_no').val("") 

            }
            else
            {
                console.log('ok');
            }
        });
  
});

    $("#doc_type").change(function () {
        var type = $('#doc_type').val();
        <?php echo 'var url ="'.asset('document/create/').'";';?>
        $('.allform').html(loadingState);
        $.ajax({    
            url:url+'/'+type,
            type: 'GET',
            success: function(data){
                $('.allform').html(data);
            }
        })
  
    });

    $('.daterange').daterangepicker({
        orientation: "auto"
    });

    $('.form-submit').on('submit',function(){
        $('.btn-submit').attr("disabled", true);
    });
</script>
