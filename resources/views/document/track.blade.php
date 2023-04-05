<?php
use App\Http\Controllers\DocumentController as Doc;
use App\User as User;
use App\Section;
use App\Http\Controllers\ReleaseController as Rel;
use App\Tracking_Releasev2;
use App\Http\Controllers\DocumentController as document;


$user = Session::get('auth');
$id = $user->id;
$section = \App\User::where('id', $id)->pluck('section')->first();

foreach($po_no as $po_num)

?>
@if(count($document))
    <style>
        .trackFontSize{
            font-size: 8pt;
        }
    </style>
    <div class="alert alert-warning">
        <div class="text-warning">
            <?php
             if ($section == '78' || $section == '79' || $section == '80' || $section == '81' || $section == '120' )
             {
                echo '<i class="fa fa-warning"></i> Documents that not accepted within 1 office day will be reported';
             }
             else
              {
                echo '<i class="fa fa-warning"></i> Documents that not accepted within 45 minutes will be reported';
                echo '<br><i class="fa fa-warning"></i> If Document is from RLED, PDOHO, and DATRC it will be reported within 1 office day';
            }
          ?>
        </div>
    </div>
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th width="14.2%">Received By</th>
            <th width="13.2%">Date In</th>
            <th width="14.2%">Subject</th>
            <th width="14.2%">For Released To</th>
            <th width="14.2%">Released by</th>
            <th width="13.2%">Released by Date/Time</th>
            <th width="14.2%">Duration</th>
            <th width="16.2%">Released Remarks</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $data = array();
        ?>
        @foreach($document as $doc)
            <?php
           $rled = "9";
           $pdoho = "10";
           $datrc = "12";
            if($doc->received_by!=0){
                $data['id'][] = $doc->id;
                if($user = User::find($doc->received_by)){
                    $sectionid = $user->section;
                    $data['received_by'][] = $user->fname.' '.$user->lname;
                    $data['section'][] = (Section::find($user->section)) ? Section::find($user->section)->description:'';
                    $division_from = (Section::find($user->section)) ? Section::find($user->section)->division:'';
                } else {
                    $data['received_by'][] = "No Name".' '.$doc->received_by;
                    $data['section'][] = "No Section";
                }
                $data['date'][] = $doc->date_in;
                $data['date_in'][] = date('M d, Y', strtotime($doc->date_in));
                $data['time_in'][] = date('h:i A', strtotime($doc->date_in));
                $data['remarks'][] = $doc->action;
                $data['status'][] = $doc->status;
                $released = Tracking_Releasev2::where("document_id","=",$doc->id)->first();
                if($released){
                    $released_div_to = (Section::find($released->released_section_to)) ? Section::find($released->released_section_to)->division:'';
                    if($released_section_to = Section::find($released->released_section_to)){
                        $data['released_section_to'][] = $released_section_to->description;
                    } else {
                        $data['released_section_to'][] = "No Data";
                    }

                    if($user = User::find($released->released_by)){
                        $sectionid = $user->section;
                        $data['released_by'][] = $user->fname.' '.$user->lname;
                        $data['released_by_section'][] = (Section::find($user->section)) ? Section::find($user->section)->description:'';
                        $division_from = (Section::find($user->section)) ? Section::find($user->section)->division:'';
                    } else {
                        $data['released_by'][] = "No Name".' '.$released->released_by;
                        $data['released_by_section'][] = "No Section";
                    }

                    $data['released_date_time'][] = $released->released_date;
                    $data['released_duration_status'][] = $released->rel_status;
                    $data['released_date'][] = date('M d, Y', strtotime($released->released_date));
                    $data['released_time'][] = date('h:i A', strtotime($released->released_date));
                    $data['released_remarks'][] = $released->remarks;
                    if($released->status == 'report' || $released->status == "waiting"  && document::checkMinutes($released->released_date) > 960
                     && ($released_div_to == $rled  || $released_div_to == $pdoho  || $released_div_to == $datrc) ){
                        $data['released_status'][] = "<small class='text-danger'><i class='fa fa-thumbs-down'></i> (Reported)</small>";
                        $data['released_alert'][] = "alert alert-danger";
                    }
                    elseif($released->status == 'report' || $released->status == "waiting"  && document::checkMinutes($released->released_date) > 960
                    && ($division_from == $rled || $division_from == $pdoho  || $division_from == $datrc) ) {
                        $data['released_status'][] = "<small class='text-danger'><i class='fa fa-thumbs-down'></i> (Reported)</small>";
                        $data['released_alert'][] = "alert alert-danger";  
                    }
                     elseif($released->status == 'report' || $released->status == "waiting"  && document::checkMinutes($released->released_date) > 45
                     && $released_div_to != $pdoho && $released_div_to != $rled && $released_div_to != $datrc
                     && $division_from != $pdoho && $division_from != $rled && $division_from != $datrc){
                        $data['released_status'][] = "<small class='text-danger'><i class='fa fa-thumbs-down'></i> (Reported)</small>";
                        $data['released_alert'][] = "alert alert-danger";
  
                    }elseif($released->status == 'accept') {
                        $data['released_status'][] = "<small style='color: #228e2f'><i class='fa fa-thumbs-up'></i> (Accepted)</small>";
                        $data['released_alert'][]  = "alert alert-success";
 
                    }
                    elseif($released->status == 'return') {
                        $data['released_status'][] = "<small style='color:#7626a6'><i class='fa fa-reply-all'></i> (Returned)</small>";
                        $data['released_alert'][]  = "";
                     
                    }
                    else {
                        $data['released_status'][] = "<small class='text-warning'><i class='fa fa-refresh'></i> (Waiting to accept)</small>";
                        $data['released_alert'][]  = "";  
                    
                    }
                } else {
                    $data['released_by_section'][] = "";
                    $data['released_by'][] = "";
                    $data['released_alert'][]  = "";
                    $data['released_section_to'][] = "";
                    $data['released_date_time'][] = "";
                    $data['released_date'][] = "";
                    $data['released_time'][] = "";
                    $data['released_remarks'][] = "";
                    $data['released_status'][] = "";
                    $data['released_duration_status'][] = "";
                }
            }
            ?>
        @endforeach
        @for($i=0;$i<count($data['received_by']);$i++)
            <?php
            $received_success = 'text-success';
            $released_info = 'text-info';
            if($data['section'][$i]=='Unconfirmed' || $data['section'][$i]=='Returned')
            {
                $class = 'text-danger text-strong';
            }
            ?>
            <tr>
                <td class="text-bold trackFontSize {{ $received_success }}">{{ $data['received_by'][$i] }}
                    <br>
                    <small class="text-warning">({{ $data['section'][$i] }})</small>
                </td>
                <td class="trackFontSize {{ $received_success }}">
                    {{ $data['date_in'][$i] }}
                    <br>
                    {{ $data['time_in'][$i] }}
                </td>
                
                <td class="trackFontSize {{ $received_success }}">{!! nl2br($data['remarks'][$i]) !!}</td>
                <td class="trackFontSize text-bold {{ $released_info }}">
                    {{ $data['released_section_to'][$i] }}
                    <br>
                    {!! $data['released_status'][$i] !!}
                </td>
                <td class="text-bold trackFontSize {{ $received_success }}">{{ $data['released_by'][$i] }}
                    <br>
                    <small class="text-warning">{{ $data['released_by_section'][$i] }}</small>
                    <br>
                </td>
                <td class="trackFontSize {{ $released_info }}">
                    {{ $data['released_date'][$i] }}
                    <br>
                    {{ $data['released_time'][$i] }}
                    <br>
                    {{ $data['released_duration_status'][$i] }}
                </td>
                <td class="trackFontSize {{ $received_success }}">
                    <?php
                    $date = date('Y-m-d H:i:s');
                    if($i == 0){
                        if(empty($data['released_date_time'][$i])){
                            if(isset($data['date'][$i+1])){
                                $start_date = $data['date'][$i];
                                $end_date = $data['date'][$i+1];
                            }
                            else{
                                $start_date = $data['date'][$i];
                                $end_date = $date;
                            }
                        }
                        else {
                            $start_date = $data['date'][$i];
                            $end_date = $data['released_date_time'][$i];
                        }
                    }
                    else{
                        if(empty($data['released_date_time'][$i-1])){
                            if(isset($data['date'][$i+1])){
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['date'][$i];
                                    $end_date = $data['date'][$i+1];
                                }
                                else {
                                    $start_date = $data['date'][$i];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                            else {
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['date'][$i];
                                    $end_date = $date;
                                }
                                else {
                                    $start_date = $data['date'][$i];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                        } else {
                            if(isset($data['date'][$i+1])){
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $data['date'][$i+1];
                                } else {
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                            else{
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $date;
                                }
                                else {
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                        }
                    }
                    ?>
                    @if($data['status'][$i]==1 && $i == count($data['received_by'])-1)
                        Cycle End
                    @else
                        {{ Rel::duration($start_date,$end_date) }}
                    @endif
                </td>
                <td class="trackFontSize {{ $released_info }}">{!! nl2br($data['released_remarks'][$i]) !!}</td>
            </tr>
        @endfor
        </tbody>
    </table>
@else
    <div class="alert alert-danger">
        <i class="fa fa-times"></i> No tracking history!
    </div>
@endif
<div class="modal-footer">

    @if($doc_type == "PR_DRUG" || $doc_type == "PR_CATERING" || $doc_type == "PR_VAN" || $doc_type == "PR_MEDSUP" || $doc_type == "PR_MEDEQ" || $doc_type == "PR_ITSUP" 
    || $doc_type == "PR_OFFSUP" || $doc_type == "PR_VEHREQM" || $doc_type == "PR_SECURITY" || $doc_type == "PRC" || $doc_type == "PRR_S" || $doc_type == "PRR_M")
            @if(count($pr_no) > 0)
            <button type="button" class="btn btn-info print_pr" onclick="window.open('{{ asset('prapi/print/'.$barcode) }}')" ><i class="fa fa-check"></i> PR</button>
             @else
             <button type="button" class="btn btn-info" disabled><i class="fa fa-check" ></i> PR</button>
             @endif
             @if(count($po_no) > 0)
            <button type="button" class="btn btn-info" onclick="window.open('{{ asset('poapi/print/'.$po_num) }}')"><i class="fa fa-check"></i> PO</button>
            @else
            <button type="button" class="btn btn-info" disabled><i class="fa fa-check" ></i> PO</button>
            @endif
    @endif

            <button type="button" class="btn btn-default cancel_track" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="button" class="btn btn-success" onclick="window.open('{{ asset('pdf/track') }}')"><i class="fa fa-print"></i> Print</button>
</div>