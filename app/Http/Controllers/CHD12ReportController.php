<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 12/8/2016
 * Time: 8:26 AM
 */


namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Http\Controllers\http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Events\DocumentNotif;
use Illuminate\Support\Facades\Session;
use App\IncidentReport;
use App\Tracking_Releasev2;
use App\chd12_incidenttype;
use App\Tracking_Filter;
use App\Tracking;
use App\User;
use App\Section;
use App\Http\Controllers\ReleaseController as rel;
use App\Transmittal_Data;
use App\Transmittal;
use App\Duration;
use App\Tracking_Details;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class CHD12ReportController extends Controller
{
    public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }
    
    public function view()
    {
        $year = Session::get('year_session');
        $month = Session::get('month_session');

        if($year)
        {
        $year = $year;
        }
        else{
        $year = date("Y");
        }

        if($month)
        {
        $month = $month;
        }
        else{
        $month = date("Y");
        }              
        $chd12_report = DB::connection('mysql')->select("CALL chd12_report('$year', '$month')");
        //print_r($chd12_report);
        return view('report.chd12report')
        ->with(compact('chd12_report'));
    }
    public function search(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        Session::put('year_session',$year);
        Session::put('month_session',$month);
        $chd12_report = DB::connection('mysql')->select("CALL chd12_report('$year', '$month')");
        //print_r($chd12_report);
        return view('report.chd12report')
        ->with(compact('chd12_report'));

    }

    public function incidentLogs(Request $req){

        if($req->daterange)
        {
            $str = $req->daterange;
            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $temp3 = array_slice($temp1, 1, 1);
        }
        else
        { 
            $end_date = date('m/d/Y'.' 12:59:59');
            $start_date = date('m/d/Y'.' 12:00:00', strtotime ( '-2 month')) ;
            $str = $start_date.' - '.$end_date;

            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $temp3 = array_slice($temp1, 1, 1);
        }
       
       
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d'.' 12:00:00',strtotime($tmp));
        // $startdate = date("Y-m-d", strtotime ( '-2 month' , strtotime ( $tmp ) )) ;


     
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d'.' 23:59:00',strtotime($tmp));

        $user = Session::get('auth');
        $id = $user->id;
        $section = \App\User::where('id', $id)->pluck('section')->first();
        $keyword = $req->keyword;
        
        if($user->user_priv == 1)
        {
            $data = DB::table('tracking_releasev2')
            ->select('tracking_releasev2.*','chd12_incidentreport.*','chd12_incidenttype.incident_type','tracking_master.description','t2.date_in')
            ->leftJoin('chd12_incidentreport', 'chd12_incidentreport.releasev2mainid', '=', 'tracking_releasev2.id')
              ->leftJoin(\DB::raw('(SELECT route_no, max(id) as maxid, max(date_in) as date_in FROM tracking_details A group by route_no) AS t2'), function($join) {
            $join->on('tracking_releasev2.route_no', '=', 't2.route_no');
             })
            ->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
            ->leftJoin('tracking_master', 'tracking_master.route_no', '=', 'tracking_releasev2.route_no')
            ->where('tracking_releasev2.status','report')
            ->whereNotNull('chd12_incidenttype.incident_type')
            ->where(function($q) use ($keyword){
                    $q->where('chd12_incidenttype.incident_type','like',"%$keyword%")
                        ->orwhere('chd12_incidentreport.reason','like',"%$keyword%")
                        ->orWhere('tracking_releasev2.route_no','like',"%$keyword%")
                        ->orWhere('tracking_master.description','like',"%$keyword%");
                                 })
            ->orderBy('t2.maxid','desc')
            ->where('chd12_incidentreport.dateencoded','>=',$startdate)
            ->where('chd12_incidentreport.dateencoded','<=',$enddate)
            ->paginate(10);
        }else
        {
        $data = DB::table('tracking_releasev2')
        ->select('tracking_releasev2.*','chd12_incidentreport.*','chd12_incidenttype.incident_type','tracking_master.description','t2.date_in')
        ->leftJoin('chd12_incidentreport', 'chd12_incidentreport.releasev2mainid', '=', 'tracking_releasev2.id')
        ->leftJoin(\DB::raw('(SELECT route_no, max(id) as maxid, max(date_in) as date_in FROM tracking_details A group by route_no) AS t2'), function($join) {
            $join->on('tracking_releasev2.route_no', '=', 't2.route_no');
        })
        ->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
        ->leftJoin('tracking_master', 'tracking_master.route_no', '=', 'tracking_releasev2.route_no')
        ->where('tracking_releasev2.status','report')  
        ->whereNotNull('chd12_incidenttype.incident_type')
        ->where('tracking_releasev2.released_section_to',$user->section)
        ->where(function($q) use ($keyword){
                $q->where('chd12_incidenttype.incident_type','like',"%$keyword%")
                    ->orwhere('chd12_incidentreport.reason','like',"%$keyword%")
                    ->orWhere('tracking_releasev2.route_no','like',"%$keyword%")
                    ->orWhere('tracking_master.description','like',"%$keyword%");
                             })
        ->orderBy('t2.maxid','desc')
        ->where('chd12_incidentreport.dateencoded','>=',$startdate)
        ->where('chd12_incidentreport.dateencoded','<=',$enddate)
        ->paginate(10);
         }

   return view('document.incident',[
       'data' => $data,
       'daterange' => $str
   ]);
       
    }

    public function incident($id){
        Session::put('addid',$id);
      $type = chd12_incidenttype::all();

     return view('document.incidentlog')
     ->with(compact('type'));
    }

    public function transIncident(){
      $type = chd12_incidenttype::all();

     return view('document.translogincident')
     ->with(compact('type'));
    }

    public function pendingIncident(Request $req)
    {   
        $user = Session::get('auth');
        $keyword = $req->keyword;

        // $start = Carbon::now()->startOfYear()->format('m/d/Y');
        // $end = Carbon::now()->endOfYear()->format('m/d/Y');
        // $start_date = date('Y/m/d'.' 12:00:00', strtotime ( '-1 month'));
        
        // $start_date = Carbon::parse($start)->startOfDay();
        // $end_date = Carbon::parse($end)->endOfDay();
        $end_date = date('Y/m/d'.' 12:59:59');
      
        $start_date = date('2023/02/01'.' 12:00:00');
        
        if($req->keyword)
        {

            $data = DB::table('tracking_releasev2')
            ->select('tracking_releasev2.*','chd12_incidentreport.*','chd12_incidenttype.incident_type','tracking_master.description','t2.date_in')
            ->leftJoin('chd12_incidentreport', 'chd12_incidentreport.releasev2mainid', '=', 'tracking_releasev2.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(id) as maxid, max(date_in) as date_in FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_releasev2.route_no', '=', 't2.route_no');
            })
            ->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
            ->leftJoin('tracking_master', 'tracking_master.route_no', '=', 'tracking_releasev2.route_no')
            ->where(function($q) use ($keyword){
                $q->where('chd12_incidenttype.incident_type','like',"%$keyword%")
                    ->orwhere('chd12_incidentreport.reason','like',"%$keyword%")
                    ->orWhere('tracking_releasev2.route_no','like',"%$keyword%")
                    ->orWhere('tracking_master.description','like',"%$keyword%");
                             })
            ->where('tracking_releasev2.status','report') 
            ->where('chd12_incidentreport.incident_typeid',null) 
            ->where('tracking_releasev2.released_section_to',$user->section)
            ->orderBy('t2.maxid','desc')
            ->where('chd12_incidentreport.dateencoded','>=',$end_date)
            ->where('chd12_incidentreport.dateencoded','<=',$start_date)
            ->paginate(10);
        }
        else
        {
        $data = DB::table('tracking_releasev2')
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
        ->orderBy('tracking_releasev2.id','desc')
        ->where('t2.date_in','>=',$start_date)
        ->where('t2.date_in','<=',$end_date)
        ->paginate(10);

         }
        return view('document.pendingincident',[
            'data' => $data
        ]);
    }

    public function addIncident(Request $req){
        if($req->relnewid)
        {
        $userid = Session::get('auth')->id;
        $date = date('Y-m-d H:i:s');
        $addid = Session::get('addid');
        $incident = new IncidentReport();
        $incident->releasev2mainid = $req->relnewid;
        $incident->incident_typeid = $req->inc;
        $incident->reason = $req->reason;
        $incident->encodedby = $userid;
        $incident->dateencoded = $date;
        $incident->save();
        Session::put('reported_docs_message','Successfully acccepted document and inserted Incident Log');

        Session::put('reported_docs',true);
        return redirect()->back();
     
        }
        else{
        $userid = Session::get('auth')->id;
        $date = date('Y-m-d H:i:s');
        $addid = Session::get('addid');
        $incident = new IncidentReport();
        $incident->releasev2mainid = $addid;
        $incident->incident_typeid = $req->inc;
        $incident->reason = $req->reason;
        $incident->encodedby = $userid;
        $incident->dateencoded = $date;
        $incident->save();
        Session::put('add_inci_message','Successfully inserted Incident Log');

        Session::put('add_inci',true);
        return redirect::back();
        }
    }

    public function editIncident($id){
        Session::put('id',$id);
        $userid = Session::get('auth')->id;
        $date = date('Y-m-d H:i:s');
        $data = IncidentReport::where('incid',$id)->first();
       return view('document.editincidentlog')
      ->with(compact('data'));

    }
    public function insertEdit(Request $req){
        $userid = Session::get('auth')->id;
        $addid = Session::get('addid');
        $date = date('Y-m-d H:i:s');
        $edit = IncidentReport::find($req->releaseid);
        $edit->incident_typeid = $req->inc;
        $edit->reason = $req->reason;
        $edit->encodedby = $userid;
        $edit->dateencoded = $date;
        $edit->save();
        return redirect('chd12report/incident');
    }

    public function release(){
        $user = Session::get('auth');
        $code2 = 'accept;'.$user->section;
        $code3 = 'return;'.$user->section;
        $id = Session::get('auth')->id;
        $section = \App\User::where('id', $id)->pluck('section')->first();
        $data = DB::table('tracking_details')
        ->select('tracking_details.route_no as route_no','tracking_details.id as tracking_id',
        'tracking_master.route_no','tracking_master.description','tracking_details.id as id',
        'tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by')
        ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
        ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
        ->leftJoin('section', 'users.section', '=', 'section.id')
        ->where(function($q) use($code2,$code3) {
            $q->where('tracking_details.code', $code2)
                ->orwhere('tracking_details.code', $code3);
        })
        ->where('section.id',$section)
        ->orderBy('tracking_details.id','desc')
        ->get();
     return view('document.release')->with(compact('data'));
    }

    public function saveRelease(Request $req){
        for($l=0;$l<15;$l++){
            if(!$req->route_no[$l])
            {
                continue;
            }

            $id = Session::get('auth')->id;
            $section = \App\User::where('id', $id)->pluck('section')->first();
            $route_no = $req->route_no[$l];
            $section1 = $req->section[$l];

            $validation = Tracking_Details::where('route_no',"=",$route_no)
            ->where('code',"=",'temp;'.$section)
            ->first();

            // if($validation)
            // {
            //     return redirect('document/release')->with('used','Please refresh Route No '. $route_no .' is already released');
            // }
            if($validation)
            {
                $error['errors'] ='Please refresh Route No '. $route_no .' is already released';
                continue;
            }else{
                $error['errors'] =null;
            }

          }
            
            for($i=0;$i<15;$i++){
                if(!$req->route_no[$i])
                {
                    continue;
                }
            $route_no = $req->route_no[$i];
            $remarks = $req->remarks[$i];
            $section = $req->section[$i];
            $section_to = $req->section[$i];
            $released_by = User::find(Session::get('auth')->id);
            $section_released_by = Section::find(Session::get('auth')->section);


            $track_id = Tracking_Details::where('route_no', $route_no)->orderBy('id', 'DESC')->pluck('id')->first();

            $doc = Tracking::where('route_no',$route_no)
                ->orderBy('id','desc')
                ->first();
            
            
                    if($doc)
                    {
                        if($track_id!=0)
                        {
                            $table = Tracking_Details::where('id', $track_id)->orderBy('id', 'DESC');
                            $code = isset($table->first()->code) ? $table->first()->code:null;
            
                            $tracking_release = new Tracking_Releasev2();
                            $tracking_release->released_by = Session::get('auth')->id;
                            $tracking_release->released_section_to = $section;
                            $tracking_release->released_date = date('Y-m-d H:i:s');
                            $tracking_release->remarks = $remarks;
                            $tracking_release->document_id = $table->first()->id;
                            $tracking_release->route_no = $route_no;
                            $tracking_release->status = "waiting";
                            $tracking_release->save();
            
                            $update = array(
                                'code' => null
                            );
            
                            $table->update($update);
                            $tmp = explode(';',$code);
                            $code = $tmp[0];
                                    if($code=='return')
                                    {
                                        $table->delete();
                                    }
                            }else{
                                        $tracking_details_id = Tracking_Details::where('route_no', $route_no)->orderBy('id', 'DESC')->pluck('id')->first();
                                        $update = array(
                                            'code' => null
                                        );
                                        $table = Tracking_Details::where('id',$tracking_details_id);
                                        $table->update($update);
                                    }
            
                            $q = new Tracking_Details();
                            $q->route_no = $route_no;
                            $q->date_in = date('Y-m-d H:i:s');
                            $q->action = $remarks;
                            $q->delivered_by = Session::get('auth')->id;
                            $q->code= 'temp;' . $section;
                            $q->save();

                            event(new DocumentNotif($section_to,$released_by,$section_released_by,$route_no));

                            rel::releasedDuration($route_no,Session::get('auth')->id);

                            $status='releaseAdded';    
                        
                        }
                        else{
                        
                        $status['errors'][] = 'Route No. "'. $route_no . '" not found in the database. ';
                        }
                 }
                 return redirect('document/release')->with('status',$status)->with('errors',$error);  
      
    }

    public function perTrans(Request $req, $TRN)
    {
        $keyword = $req->keyword;
        $status = Transmittal::where('trn',$TRN)->pluck('status')->first();
        if($req->keyword)
        {
            $data = Transmittal_Data::select('transmittal.*','transmittal_data.*','released_by.fname as fname','released_by.lname as lname','transmittal_data.id as transdata_id')
            ->leftJoin('transmittal','transmittal_data.trn','=','transmittal.trn')
            ->leftJoin('users as released_by','released_by.id','=','transmittal_data.released_by')
            
            ->where('transmittal.trn',$req->trn)
            ->where(function($q) use ($keyword){
                $q->where('transmittal_data.route_no','like',"%$keyword%");
            })
            ->orderby('transmittal.id','desc')
            ->paginate(15);
        }
        else
        {
         $data = Transmittal_Data::select('transmittal.*','transmittal_data.*','released_by.fname as fname','released_by.lname as lname','transmittal_data.id as transdata_id')
        ->leftJoin('transmittal','transmittal_data.trn','=','transmittal.trn')
        ->leftJoin('users as released_by','released_by.id','=','transmittal_data.released_by')
        
        ->where('transmittal.trn',$TRN)
        ->orderby('transmittal.id','desc')
        ->paginate(15);
        }

        return view ('document.per_transmittal',[
            'data' => $data,
            'TRN' => $TRN,
            'desc' => '',
            'status' => $status
        ]);
    }

    public function Transmittal(Request $req)
    {
        $user = Session::get('auth');
        $keyword = $req->keyword;

        if($req->keyword && $user->user_priv == 1)
        {
            $data = Transmittal::select('transmittal.*', 'encoded_by.fname as fname','encoded_by.lname as lname')
            ->leftJoin('users as encoded_by','encoded_by.id','=','transmittal.encoded_by')
                    ->where(function($q) use ($keyword){
                        $q->where('transmittal.trn','like',"%$keyword%");
                    })
                    
                      ->orderby('transmittal.id','desc')
                      ->paginate(15);
        }
        else if($user->user_priv == 1)
        {
            $data = Transmittal::select('transmittal.*', 'encoded_by.fname as fname','encoded_by.lname as lname')
            ->leftJoin('users as encoded_by','encoded_by.id','=','transmittal.encoded_by')
                      ->orderby('transmittal.id','desc')
                      ->paginate(15);
        }
        else if($req->keyword)
        {
            $data = Transmittal::select('transmittal.*', 'encoded_by.fname as fname','encoded_by.lname as lname')
            ->leftJoin('users as encoded_by','encoded_by.id','=','transmittal.encoded_by')
            ->where('encoded_by.section',$user->section)
                    ->where(function($q) use ($keyword){
                        $q->where('transmittal.trn','like',"%$keyword%");
                    })
                    
                      ->orderby('transmittal.id','desc')
                      ->paginate(15);
        }
        else
        {
        $data = Transmittal::select('transmittal.*', 'encoded_by.fname as fname','encoded_by.lname as lname')
              ->leftJoin('users as encoded_by','encoded_by.id','=','transmittal.encoded_by')
              ->where('encoded_by.section',$user->section)
                        ->orderby('transmittal.id','desc')
                        ->paginate(15);
        }

        return view ('document.transmittal',[
            'data' => $data,
            'keyword' => $keyword
        ]);
    }

    public function TransBody(Request $req)
    {
        $desc = $req->desc;
        $user = Session::get('auth');
        $user_id = Session::get('auth')->id;
        if($req->act_trn)
        {
            $TRN =$req->act_trn;
            $trn_code = '1';
        }
        else{
            $TRN = $user->section.'-'.Session::get('auth')->id.date('mdHis');
            $trn_code = '0';
        }
        $code = 'temp;'.$user->section;
        $code2 = 'accept;'.$user->section;
        $code3 = 'return;'.$user->section;

        // if($req->valid == 'add'){
        //     $documents = Tracking_Details::select(
        //         'tracking_details.*',
        //         'tracking_master.doc_type as doc_type',
        //         'tracking_master.prepared_date as prepared_date'
        //     )
        //       ->leftJoin('tracking_master','tracking_details.route_no','=','tracking_master.route_no')
        //       ->leftJoin('users','tracking_details.delivered_by','=','users.id')
        //       ->where('tracking_details.code','like',"%temp%")
        //         ->where('users.section',$user->section)
        //         ->where('tracking_details.status',0)
        //         ->orderBy('tracking_details.date_in','desc')
        //         ->whereNotExists(function($query)use($TRN)
        //                     {
        //                         $query->select(DB::raw(1))
        //                             ->from('transmittal_data')
        //                             ->whereRaw('tracking_details.route_no = transmittal_data.route_no')
        //                             ->where('transmittal_data.trn',$TRN);
        //                     })  
        //         ->get();
        //     }
        //     else if($req->valid == 'create')
        //     {
            if($user->section == 83)
            {
                $documents = Tracking_Details::select(
                    'tracking_details.*',
                    'tracking_master.doc_type as doc_type',
                    'tracking_master.prepared_date as prepared_date',
                    'delivered_by.fname',
                    'delivered_by.lname',
                )
                    ->leftJoin('tracking_master','tracking_details.route_no','=','tracking_master.route_no')
                    ->leftJoin('users as delivered_by','tracking_details.delivered_by','=','delivered_by.id')
                    ->where('tracking_details.code','like',"%temp%")
                    ->where('delivered_by.section',$user->section)
                    // ->where('tracking_details.delivered_by',$user->id)
                    ->where('tracking_details.status',0)
                    ->orderBy('tracking_details.date_in','desc')
                    // ->whereNotExists(function($query)use($user_id)
                    //             {
                    //                 $query->select(DB::raw(1))
                    //                     ->from('transmittal_data')
                    //                     ->where('transmittal_data.released_by',$user_id)
                    //                     ->whereRaw('tracking_details.route_no = transmittal_data.route_no');
                    //             })  
                    ->get();
            }
            else{
                $documents = Tracking_Details::select(
                    'tracking_details.*',
                    'tracking_master.doc_type as doc_type',
                    'tracking_master.prepared_date as prepared_date',
                    'delivered_by.fname',
                    'delivered_by.lname',
                )
                    ->leftJoin('tracking_master','tracking_details.route_no','=','tracking_master.route_no')
                    ->leftJoin('users as delivered_by','tracking_details.delivered_by','=','delivered_by.id')
                    ->where('tracking_details.code','like',"%temp%")
                    ->where('delivered_by.section',$user->section)
                    ->where('tracking_details.delivered_by',$user->id)
                    ->where('tracking_details.status',0)
                    ->orderBy('tracking_details.date_in','desc')
                    // ->whereNotExists(function($query)use($user_id)
                    //             {
                    //                 $query->select(DB::raw(1))
                    //                     ->from('transmittal_data')
                    //                     ->where('transmittal_data.released_by',$user_id)
                    //                     ->whereRaw('tracking_details.route_no = transmittal_data.route_no');
                    //             })  
                    ->get();
            }
            // }

        return view('document.transmittal_body',[
            'trn' => $TRN,
            'trn_code' => $trn_code,
            'desc' => $desc,
            'documents' => $documents
        ]);
    }

    public function wholetransDelete(Request $req)
    {
        $trn = $req->trn_delete;

        Transmittal::where('trn',$trn)->delete();
        Transmittal_Data::where('trn',$trn)->delete();

        Session::put('wholetrans_delete',true);

        return redirect::back();
    }

    public function checkTrans(Request $req)
    {   
        $trn = $req->trn;

        $data = Transmittal_Data::all()
                ->where('trn',$trn);

        return $data;
    }

    public function TransDelete(Request $req)
    {
        if($req->id)
        {
        Transmittal_Data::find($req->id)->delete();
    }
        Session::put('trans_delete',true);

        return redirect::back();
    }
    
    public function multiPrint(Request $req)
    {
        $user = Session::get('auth');
        $trn = $req->act_trn;

        $id = $user->id;
        $route_no = $req->route_no;

        if($req->trn_code == 1)
        {
            foreach($route_no as $route)
            {
                $info = DB::table('tracking_details')
                    ->select('tracking_details.*','delivered_by.id as delivered_id','tracking_master.doc_type as doc_type')
                   ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                   ->leftJoin('users as received_by', 'tracking_details.received_by', '=', 'received_by.id')
                   ->leftJoin('users as delivered_by', 'tracking_details.delivered_by', '=', 'delivered_by.id')
                   ->where('tracking_details.route_no', $route)
                    ->orderBy('tracking_details.id','desc')
                    ->first();
                    
               $data2 = array(
                    'trn' => $req->act_trn,
                    'route_no' => $route,
                    'doc_type' => $info->doc_type,
                    'released_by' => $info->delivered_id
               );
    
               Transmittal_Data::create($data2);
            }
        }
        else
        {
        foreach($route_no as $route)
        {
            $info = DB::table('tracking_details')
                ->select('tracking_details.*','delivered_by.id as delivered_id','tracking_master.doc_type as doc_type')
               ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
               ->leftJoin('users as received_by', 'tracking_details.received_by', '=', 'received_by.id')
               ->leftJoin('users as delivered_by', 'tracking_details.delivered_by', '=', 'delivered_by.id')
               ->where('tracking_details.route_no', $route)
                ->orderBy('tracking_details.id','desc')
                ->first();
                
           $data1 = array(
               'trn' => $trn,
               'encoded_by' => $id,
               'status' =>  'draft',
               'description' => $req->desc
           );

           $data2 = array(
                'trn' => $trn,
                'route_no' => $route,
                'doc_type' => $info->doc_type,
                'released_by' => $info->delivered_id
           );

           Transmittal_Data::create($data2);
        }
        Transmittal::create($data1);
     }
        Session::put('add_trans',true);

        return redirect::back();
    }

    public function printTrans($TRN)
    {
        $user = Session::get('auth');
        $id = $user->id;
       $route_no = Transmittal_Data::select('route_no')
                    ->where('trn',$TRN)
                    ->get();

        foreach($route_no as $route)
        {
            $data = DB::table('tracking_details')
               ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
               ->where('tracking_details.route_no',$route->route_no)
                ->orderBy('date_in','desc')
                ->first();

            $result[] = $data;
        }

       
    return view('logs.multiple',[
        'result' => $result,
        'TRN' => $TRN
    ]);

    }
    

    public function secPrint(Request $req)
    {
        $user = Session::get('auth');
        $id = $user->id;
        $route_no = $req->route_no;

        foreach($route_no as $route)
        {

            $data = DB::table('tracking_details')
               ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
               ->where('tracking_details.route_no',$route)  
                ->orderBy('date_in','desc')
                ->first();
            $result[] = $data;

        }
       
    
    return view('logs.multiple')->with(compact('result'));

    }

    public function aveLogs (Request $req){
        $section = $req->section;

        if($req->section){
        $data = DB::table('vw_ave_document')
                ->where('totalminutes','!=','null')
                ->where('section',$section)
                ->paginate(20);
        }else{
            $data = DB::table('vw_ave_document')
                ->where('totalminutes','!=','null')
                ->paginate(20);
        }

        return view('report.average',[
            'data' => $data,
            'cur_section' => $section
        ]);
    }

    public function secDuration(Request $req)
    {
       
        // $data = DB::table('tracking_details')
        // ->selectRaw('tracking_details.route_no, tracking_master.doc_type, section.description, tracking_details.date_in
        // , tracking_details.received_by, tracking_details.status,tracking_releasev2.released_date ')
        // ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
        // ->distinct('tracking_details.route_no')
        // ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
        // ->leftJoin('section', 'users.section', '=', 'section.id')
        // ->leftJoin('tracking_releasev2', 'tracking_details.id', '=', 'tracking_releasev2.document_id')
        // ->where('section.id',115)
        // ->where('tracking_details.status','!=',1)
        // ->paginate(1000)
        // ->get();

    //    $data = DB::table('vw_avg_documentcount_persection')
    //    ->where('sectionid',115)
    //    ->get();
    
    if($req->newyear)
    {
        $year = $req->newyear;
    }else{
    $year = date('Y');
    }

    if($req->section)
    {
    $section = $req->section;
    $data = DB::connection('mysql')->select("CALL chd12_persecdoc_avg('$section','$year')");
    }else
    {
    $section = Session::get('auth')->section;
    $data = DB::connection('mysql')->select("CALL chd12_persecdoc_avg('$section','$year')");
    }
        return view('report.secduration',[
            'data' => $data,
            'cur_section' => $section,
            'newyear' => $year
        ]);
    }

    public function filterBody(Request $req)
    {
        $data = Tracking_Filter::find($req->doc_id);

        return view('document.filter_body',[
            'data' => $data
        ]);
    }

    public function filterOptions(Request $req)
    {
        $data = array(
               'doc_type' => $req->doc_type,
               'doc_description' => $req->doc_description,
               'description' => 1
        );

        if($req->id){
            Tracking_Filter::find($req->id)->update($data);
            Session::put('filter_message','Successfully updated document');
        } else {
            Tracking_Filter::create($data);
            Session::put('filter_message','Successfully added document');
        }

        Session::put('filter',true);
        return Redirect::back();
    }

    public function filterDelete(Request $req)
    {
        $doc_type = $req->doc_type;

        $data = Tracking::where('doc_type',$doc_type)
        ->count();

        if($data > 0)
        {
            Session::put('filter_message_delete','Cant delete document, Document has data');
        }
        else
        {
            Tracking_Filter::where('doc_type',$doc_type)
            ->delete();
            Session::put('filter_message_delete','Successfully Deleted');
        }

        Session::put('filter_delete',true);
        return Redirect::back();
    }
    public function top10(Request $req)
    {
        if($req->daterange)
        {
            $daterange = $req->daterange;

            $str = $daterange;
            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $tmp = implode(',', $temp2);
            $start = date('Y-m-d'.' 12:00:00',strtotime($tmp));
        
            $temp3 = array_slice($temp1, 1, 1);
            $tmp = implode(',', $temp3);
            $end = date('Y-m-d'.' 23:59:00',strtotime($tmp));

        }
        else
        {
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
            $daterange = "01/01/$year - 12/31/$year";
            $str = $daterange;
        }

        if($req->type == 'reported')
        {
            $type = $req->type;
            $year = $req->year;
            $quarter = $req->quarter;

            $top10 = DB::connection('mysql')->select("CALL chd12_top10rep('$start','$end')");

            
            $from=strtotime($start);
            $fmonth=date("F",$from);
            $fyear=date("Y",$from);

            $to=strtotime($end);
            $tmonth=date("F",$to);
            $tyear=date("Y",$to);

            $title= $fmonth." - ".$tmonth." $tyear"." Reported Documents";
        }
        elseif($req->type == 'duration')
        {
            $type = $req->type;
            $year = $req->year;
            $quarter = $req->quarter;

            $top10 = DB::connection('mysql')->select("CALL chd12_top10dur('$start','$end')");

            
            $from=strtotime($start);
            $fmonth=date("F",$from);
            $fyear=date("Y",$from);

            $to=strtotime($end);
            $tmonth=date("F",$to);
            $tyear=date("Y",$to);

            $title= $fmonth." - ".$tmonth." $tyear"." Lapsed Documents";
        }
        else
        {
            $type = 'duration';
            $year = date("Y");
            $month = date("n");
            //Calculate the year quarter.
            $quarter = ceil($month / 3);

            $top10 = DB::connection('mysql')->select("CALL chd12_top10dur('$start','$end')");

            
            $from=strtotime($start);
            $fmonth=date("F",$from);
            $fyear=date("Y",$from);

            $to=strtotime($end);
            $tmonth=date("F",$to);
            $tyear=date("Y",$to);

            $title= $fmonth." - ".$tmonth." $tyear"." Lapsed Documents";
        }

        return view('report.top10',[
            'newyear' => $year,
            'title' => $title,
            'quarter' => $quarter,
            'type' => $type,
            'daterange' => $str,
            'top10' => $top10
        ]);

    }

    public function least10(Request $req)
    {

        if($req->daterange)
        {
            $daterange = $req->daterange;

            $str = $daterange;
            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $tmp = implode(',', $temp2);
            $start = date('Y-m-d'.' 12:00:00',strtotime($tmp));
        
            $temp3 = array_slice($temp1, 1, 1);
            $tmp = implode(',', $temp3);
            $end = date('Y-m-d'.' 23:59:00',strtotime($tmp));

        }
        else
        {
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
            $daterange = "01/01/$year - 12/31/$year";
            $str = $daterange;
        }
        
        if($req->type == 'reported')
        {
            $type = $req->type;
            $year = $req->year;
            $quarter = $req->quarter;

            $least10 = DB::connection('mysql')->select("CALL chd12_least10rep('$start','$end')");
        }
        elseif($req->type == 'duration')
        {
            $type = $req->type;
            $year = $req->year;
            $quarter = $req->quarter;

            $least10 = DB::connection('mysql')->select("CALL chd12_least10dur('$start','$end')");
        }
        else
        {
            $type = 'duration';
            $year = date("Y");
            $month = date("n");
            //Calculate the year quarter.
            $quarter = ceil($month / 3);

            $least10 = DB::connection('mysql')->select("CALL chd12_least10dur('$start','$end')");
        }

        return view('report.least10',[
            'newyear' => $year,
            'quarter' => $quarter,
            'daterange' => $str,
            'type' => $type,
            'least10' => $least10
        ]);

    }

    public function docRoute(Request $req)
    {
        return view('document.allform',[
            'doc_type' => $req->doc_type
        ]);
    }
    public function getDesc(Request $req)
    {
        $results = Tracking_details::where('route_no',$req->route_no)
        ->orderby('id','asc')
        ->first();
    
        return $results;
    }
    public function checkStatus(Request $req)
    {
        $result = Tracking_Releasev2::where('route_no',$req->route_no)
        ->orderby('id','desc')
        ->first();

        Session::put('new_addid',$result->id);
    
        return $result;
    }


    public function allDuration(Request $req)
    {
        $keyword = $req->keyword;

        $data = Duration::all();

        $data = DB::table('duration')
        ->select('duration.*','section.description as section','tracking_filter.doc_description as description')
        ->leftjoin('section','duration.section','=','section.id')
        ->leftjoin('tracking_filter','duration.doc_type','=','tracking_filter.doc_type')
          ->where(function($q) use ($keyword){
                $q->where('section.description','like',"%$keyword%")
                    ->orwhere('tracking_filter.description','like',"%$keyword%");
            })
        ->orderby('id','desc')
        ->paginate(10);

        return view('document.duration',[
            'data' => $data
        ]);

    }
    public function durationBody(Request $req)
    {

        $data = DB::table('duration')
        ->select('duration.*','section.id as sectionid','tracking_filter.doc_type as doc_type')
        ->leftjoin('section','duration.section','=','section.id')
        ->leftjoin('tracking_filter','duration.doc_type','=','tracking_filter.doc_type')
        ->where('duration.id',$req->dur_id)
        ->first(); 
        return view('document.duration_body',[
            'data' => $data
        ]);
    }

    public function durationDelete(Request $req)
    {
        Duration::find($req->dur_id)->delete();

        Session::put('duration_delete',true);
        return Redirect::back();
    }

    
    public function durationOptions(Request $req)
    {
    
        $data = $req->all();
        if($req->id)
        {
            Duration::find($req->id)->update($data);
            Session::put('duration_message','Document successfully updated');
        }
        else
        {

            $all = Duration::all();

            foreach($all as $val)
            {
               if($req->doc_type == $val->doc_type && $req->section == $val->section)
               {
                Session::put('duration_message','Cant add document, Document already exist');
    
                Session::put('duration',true);
                return Redirect::back();
               }
            }
            
            Duration::create($data);
            Session::put('duration_message','Document successfully added');
        }

        Session::put('duration',true);
        return Redirect::back();
        
    }

    static function durationMinutes($start_date,$end_date)
    {
        $startTime = Carbon::parse($start_date);
        $endTime = Carbon::parse($end_date);
    
        $totalDuration = $endTime->diff($startTime);
        $diffInMinutes = $totalDuration;

        $minutes = $diffInMinutes->days * 24 * 60;
        $minutes += $diffInMinutes->h * 60;
        $minutes += $diffInMinutes->i;
    
        return $minutes;
    }
    public function transaddInci(Request $req)
    {
     $data = Session::get('transmittal_lapsed');
     $userid = Session::get('auth')->id;

     foreach($data as $dat)
     {
         $data = array(
             'releasev2mainid' => $dat,
             'incident_typeid' => $req->inc,
             'reason' => $req->reason.'<br>'.$req->trn,
             'encodedby' => $userid,
             'dateencoded' => date('Y-m-d H:i:s'),
             'trn' => $req->trn
         );
         IncidentReport::create($data);
     }
     Session::put('transincdi_add',true);
     return redirect::back();
    }

    public function transinciLogs(Request $req)
    {
        
        if($req->daterange)
        {
            $str = $req->daterange;
            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $temp3 = array_slice($temp1, 1, 1);
        }
        else
        { 
            $end_date = date('m/d/Y'.' 12:59:59');
            $start_date = date('m/d/Y'.' 12:00:00', strtotime ( '-2 month')) ;
            $str = $start_date.' - '.$end_date;

            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $temp3 = array_slice($temp1, 1, 1);
        }
       
       
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d'.' 12:00:00',strtotime($tmp));
        // $startdate = date("Y-m-d", strtotime ( '-2 month' , strtotime ( $tmp ) )) ;


     
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d'.' 23:59:00',strtotime($tmp));

        $user = Session::get('auth');
        $id = $user->id;
        $section = \App\User::where('id', $id)->pluck('section')->first();
        $keyword = $req->keyword;
        
        if($user->user_priv == 1)
        {
            $data = DB::table('tracking_releasev2')
            ->select('tracking_releasev2.*','chd12_incidentreport.*','chd12_incidenttype.incident_type','tracking_master.description','t2.date_in')
            ->leftJoin('chd12_incidentreport', 'chd12_incidentreport.releasev2mainid', '=', 'tracking_releasev2.id')
              ->leftJoin(\DB::raw('(SELECT route_no, max(id) as maxid, max(date_in) as date_in FROM tracking_details A group by route_no) AS t2'), function($join) {
            $join->on('tracking_releasev2.route_no', '=', 't2.route_no');
             })
            ->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
            ->leftJoin('tracking_master', 'tracking_master.route_no', '=', 'tracking_releasev2.route_no')
            ->where('tracking_releasev2.rel_status','lapsed')
            ->whereNotNull('chd12_incidenttype.incident_type')
            ->where(function($q) use ($keyword){
                    $q->where('chd12_incidenttype.incident_type','like',"%$keyword%")
                        ->orwhere('chd12_incidentreport.reason','like',"%$keyword%")
                        ->orWhere('tracking_releasev2.route_no','like',"%$keyword%")
                        ->orWhere('tracking_master.description','like',"%$keyword%");
                                 })
                                 ->whereExists(function($query)
                                 {
                             $query->select(DB::raw(1))
                                ->from('transmittal_data')
                                 ->whereRaw('t2.route_no = transmittal_data.route_no');
                                   })  
            ->orderBy('t2.maxid','desc')
            ->where('chd12_incidentreport.dateencoded','>=',$startdate)
            ->where('chd12_incidentreport.dateencoded','<=',$enddate)
            ->paginate(10);
        }else
        {
        $data = DB::table('tracking_releasev2')
        ->select('tracking_releasev2.*','chd12_incidentreport.*','chd12_incidenttype.incident_type','tracking_master.description','t2.date_in')
        ->leftJoin('chd12_incidentreport', 'chd12_incidentreport.releasev2mainid', '=', 'tracking_releasev2.id')
        ->leftJoin(\DB::raw('(SELECT route_no, max(id) as maxid, max(date_in) as date_in FROM tracking_details A group by route_no) AS t2'), function($join) {
            $join->on('tracking_releasev2.route_no', '=', 't2.route_no');
        })
        ->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
        ->leftJoin('tracking_master', 'tracking_master.route_no', '=', 'tracking_releasev2.route_no')
        ->where('tracking_releasev2.rel_status','lapsed')
        ->whereNotNull('chd12_incidenttype.incident_type')
        ->where('tracking_releasev2.released_section_to',$user->section)
        ->where(function($q) use ($keyword){
                $q->where('chd12_incidenttype.incident_type','like',"%$keyword%")
                    ->orwhere('chd12_incidentreport.reason','like',"%$keyword%")
                    ->orWhere('tracking_releasev2.route_no','like',"%$keyword%")
                    ->orWhere('tracking_master.description','like',"%$keyword%");
                             })
        ->whereExists(function($query)
             {
         $query->select(DB::raw(1))
            ->from('transmittal_data')
             ->whereRaw('t2.route_no = transmittal_data.route_no');
               })  
                             
        ->orderBy('t2.maxid','desc')
        ->where('chd12_incidentreport.dateencoded','>=',$startdate)
        ->where('chd12_incidentreport.dateencoded','<=',$enddate)
        ->paginate(10);
         }

   return view('document.transincidentlogs',[
       'data' => $data,
       'daterange' => $str
   ]);
    }


    public function transupStatus(Request $req)
    {
       $update = Transmittal::where('trn',$req->trn)->update([
            'status' => 'completed'
        ]);

        if($update)
        {
            Session::put('transupstat_ok',true);
        }
        else{
            Session::put('transupstat_not',true);
        }

    }

    public function allacceptedDocs($sec_id,$year,$month, Request $req)
    {
        $user = Session::get('auth');
        $keyword = $req->keyword;

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-31 23:59:59';

        $data =  DB::table('tracking_details')
        ->select('*')
        ->leftjoin('users','tracking_details.received_by','=','users.id')
        ->where(function($q) use ($keyword){
                $q->where('tracking_details.route_no','like',"%$keyword%")
                    ->orWhere('tracking_details.action','like',"%$keyword%");
                             })
        ->where('tracking_details.date_in','>=',$start)
         ->where('tracking_details.date_in','<=',$end)
         ->where('users.section',$sec_id)
         ->get();

        //  $data =  DB::table('tracking_master')
        //  ->select('*')
        //  ->leftjoin('users','tracking_master.prepared_by','=','users.id')
        //  ->where('tracking_master.prepared_date','>=',$start)
        //   ->where('tracking_master.prepared_date','<=',$end)
        //   ->where('users.section',86)
        //   ->get();

        //   $data =  DB::table('tracking_releasev2')
        //   ->select('*')
        //   ->leftjoin('section','tracking_releasev2.released_section_to','=','section.id')
        //   ->where('tracking_releasev2.released_date','>=',$start)
        //    ->where('tracking_releasev2.released_date','<=',$end)
        //    ->where('section.id',86)
        //    ->where('tracking_releasev2.status','report')
        //    ->get();

        // $data =  DB::table('tracking_releasev2')
        //    ->select('*')
        //    ->leftjoin('section','tracking_releasev2.released_section_to','=','section.id')
        //    ->where('tracking_releasev2.released_date','>=',$start)
        //     ->where('tracking_releasev2.released_date','<=',$end)
        //     ->where('section.id',86)
        //     ->where('tracking_releasev2.status','waiting')
        //     ->get();
      
        return view('report.allaccepted',[
            'data' => $data,
            'id' => $sec_id,
            'year' => $year,
            'month' => $month
        ]);

    }

    public function allcycleendDocs($sec_id,$year,$month, Request $req)
    {
      
        $user = Session::get('auth');

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-31 23:59:59';

        $data = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$sec_id','1')");


          return view('report.allcycleend',[
            'data' => $data,
            'id' => $sec_id,
            'year' => $year,
            'month' => $month
        ]);
    }

    public function allCreatedDoc($sec_id,$year,$month, Request $req)
    {
      
        $user = Session::get('auth');

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-31 23:59:59';

        $data =  DB::table('tracking_master')
         ->select('*')
         ->leftjoin('users','tracking_master.prepared_by','=','users.id')
         ->where('tracking_master.prepared_date','>=',$start)
          ->where('tracking_master.prepared_date','<=',$end)
          ->where('users.section',$sec_id)
          ->get();


          return view('report.allcreated',[
            'data' => $data,
            'id' => $sec_id,
            'year' => $year,
            'month' => $month
        ]);
    }

    public function allreportedDocs($sec_id,$year,$month)
    {
        $user = Session::get('auth');

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-31 23:59:59';


           $data =  DB::table('tracking_releasev2')
          ->select('*')
          ->leftjoin('section','tracking_releasev2.released_section_to','=','section.id')
          ->where('tracking_releasev2.released_date','>=',$start)
           ->where('tracking_releasev2.released_date','<=',$end)
           ->where('section.id',$sec_id)
           ->where('tracking_releasev2.status','report')
           ->get();

         
           return view('report.allreported',[
            'data' => $data,
            'id' => $sec_id,
            'year' => $year,
            'month' => $month
        ]);
    }

    public function allwaitingDocs($sec_id,$year,$month)
    {
        $user = Session::get('auth');

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-31 23:59:59';


         $data =  DB::table('tracking_releasev2')
           ->select('*')
           ->leftjoin('section','tracking_releasev2.released_section_to','=','section.id')
           ->where('tracking_releasev2.released_date','>=',$start)
            ->where('tracking_releasev2.released_date','<=',$end)
            ->where('section.id',$sec_id)
            ->where('tracking_releasev2.status','waiting')
            ->get();

            return view('report.allwaiting',[
                'data' => $data,
            'id' => $sec_id,
            'year' => $year,
            'month' => $month
            ]);
    }

}   
