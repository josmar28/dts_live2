<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
$user = Session::get('auth');

// $start = Carbon::now()->startOfYear()->format('m/d/Y');
// $end = Carbon::now()->endOfYear()->format('m/d/Y');



// $start_date = date('Y/m/d'.' 12:00:00', strtotime ( '-1 month'));
// $start_date = Carbon::parse($start)->startOfDay();
// $end_date = Carbon::parse($end)->endOfDay();

$end_date = date('Y/m/d'.' 12:59:59');
$start_date = date('2022/03/01'.' 12:00:00');


$incident =  DB::table('tracking_releasev2')
->select('tracking_releasev2.*','chd12_incidentreport.*','chd12_incidenttype.incident_type','tracking_master.description','t2.date_in')
->leftJoin('chd12_incidentreport', 'chd12_incidentreport.releasev2mainid', '=', 'tracking_releasev2.id')
->leftJoin(\DB::raw('(SELECT route_no, max(id) as maxid, max(date_in) as date_in FROM tracking_details A group by route_no) AS t2'), function($join) {
    $join->on('tracking_releasev2.route_no', '=', 't2.route_no');
})
->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
->leftJoin('tracking_master', 'tracking_master.route_no', '=', 'tracking_releasev2.route_no')
->where('tracking_releasev2.status','report') 
->where('chd12_incidentreport.incident_typeid',null) 
->where('tracking_releasev2.released_section_to',$user->section)
->where('t2.date_in','>=',$start_date)
->where('t2.date_in','<=',$end_date)
->count();
?>

<div class="modal fade" tabindex="-1" role="dialog" id="penModal" style="margin-top: 30px;z-index: 99999 ">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h3 style="font-weight: bold" class="text-danger">Caution!</h3>
                <div class="text-danger">
                   <i class="fa fa-phone-exclamation"></i> You have {{$incident}} Pending Incident logs now. Please fill out
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@if($incident > 0)
<script>
    $('#penModal').modal('show');
</script>
@endif