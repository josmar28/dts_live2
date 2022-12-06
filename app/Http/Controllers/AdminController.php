<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/18/2016
 * Time: 8:56 AM
 */

namespace App\Http\Controllers;
use App\Designation;
use App\Division;
use Illuminate\Http\Request;
use App\User;
use App\Users;
use App\Section;
use App\Tracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use App\Tracking_Releasev2;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function __construct()
    {

        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
        
    }
    public function users(Request $request) {

        
        $users = User::where('id','!=', $request->id)
                    ->paginate(10);
        return view('users.users')
                ->with('users',$users);
    }
    public function user_create(Request $request){

        if($request->isMethod('get')){
            $div = Division::all();
            $dis = Designation::all();
            return view('users.new')
                ->with('div', $div)
                ->with('dis', $dis);
        }
        if($request->isMethod('post')){
            $user = User::where('username', $request->input('username'))->first();
            if(isset($user)) {
                return redirect('users')->with('used','Username is already used.');
            }
            $user = new User();
            $user->fname = $request->input('fname');
            $user->mname = $request->input('mname');
            $user->lname = $request->input('lname');
            $user->password = bcrypt($request->input('password'));
            $user->username = $request->input('username');
            $user->designation = $request->input('designation');
            $user->division = $request->input('division');
            $user->section = $request->input('section');
            $user->user_priv = $request->input('user_type');
            $user->status = 1;
            $user->save();
            return redirect('users');
        }
    }
    public function user_edit(Request $request){

        $user = User::find($request->input('id'));
       // syslog($request);
        //GET
        if($request->isMethod('get')){
            if(isset($user)) {
                return view('users.edit')
                    ->with('user', $user)
                    ->with('section',Section::all())
                    ->with('division',Division::all())
                    ->with('designation',Designation::all());
            }
        }
        //POST
        if($request->isMethod('post')){
            $username = '';
            if($user->username == $request->input('username')) {
                $username = $request->input('username');
            } else {
                $user = User::where('username', $request->input('username'))->first();
                if(isset($user) and count($user) > 0) {
                    return redirect('users')->with('used','Username is already used.');
                }
            }

            $user = User::find($request->input('id'));
            $user->fname = $request->input('fname');
            $user->mname = $request->input('mname');
            $user->lname = $request->input('lname');
            $user->username = $request->username;
            $user->designation = $request->input('designation');
            $user->division = $request->input('division');
            $user->section = $request->input('section');
            $user->user_priv = $request->input('user_type');
            if($request->reset_pass) {
                $user->password = bcrypt($request->input('reset_pass'));
            }
            $user->save();
            return redirect('users');
        }
    }
    public function section(Request $request) {
        $section = Section::where('division',$request->input('id'))->get();
        if(isset($section) and count($section) > 0) {
            return view('users.tr')->with('section', $section);
        }
    }

    public function search(Request $request) {
        $keyword = $request->input('search');
        $user = User::where(function($q) use ($keyword){
                    $q->where('fname','LIKE', "%". $keyword ."%")
                    ->orWhere('mname', 'LIKE', "%". $keyword."%")
                    ->orWhere('lname', 'LIKE', "%". $keyword. "%")
                    ->orWhere('username' ,'LIKE', "%". $keyword. "%");
                                 })
                    ->paginate(10);
        if(isset($user) and count($user) > 0) {
            return view('users.users')->with('users',$user);
        }
        return view('users.users')->with('users', $user);
    }
    public function remove(Request $request){
        $user = User::find($request->id);
        if(isset($user)){
            $user->update([
                'status' => "0"
            ]);
            return json_encode(array('status' => 'ok'));

            // return $user->status;
        }
    }
    public function check_user(Request $request)
    {
        $user = User::where('username', $request->input('username'))->first();
        if (isset($user) and count($user) > 0) {
            return json_encode(array('status' => 'ok'));
        }
        return json_encode(array('status' => 'false'));
    }

    function report(){
        $year = '2021';
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';
        $divison = Division::orderBy('description','asc')->get();
        return view('report.documents',['division'=>$divison]);
    }

    public function reportedDocuments($year){
        $data = [];
        $reportedDocument = DB::connection('mysql')->select("call reportedDocument($year)");
        foreach($reportedDocument as $row){
            $data[$row->section.'-'.$row->month] = $row->reported;
        }

        return view('report.reportedDocuments',[
            "year" => $year,
            "reportedDocument" => $data
        ]);
    }

    public function reportDates(Request $request)
    {
        Session::put('report_year',$request->year);
        Session::put('report_month',$request->month);

        return redirect()->back();
    }

    static function countAccepted($section){
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');
        if($report_year != 0 && $report_month != 0 )
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $accepted = DB::table('tracking_details')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('tracking_details.date_in','>=',$start)
            ->where('tracking_details.date_in','<=',$end)
            ->where('section.id',$section)
            ->count();

        }
        elseif( $report_month != 0 )
        {
            $report_year = date("Y"); 
         
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $accepted = DB::table('tracking_details')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('tracking_details.date_in','>=',$start)
            ->where('tracking_details.date_in','<=',$end)
            ->where('section.id',$section)
            ->count();
        }
        else
        {

        $year = date("Y");
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';

        $accepted = DB::table('tracking_details')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('tracking_details.date_in','>=',$start)
            ->where('tracking_details.date_in','<=',$end)
            ->where('section.id',$section)
            ->count();
        }
        return $accepted;
      
    }

    static function allcountAccepted($section){
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');
        if($report_year != 0 && $report_month != 0 )
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $accepted = DB::table('tracking_details')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('tracking_details.date_in','>=',$start)
            ->where('tracking_details.date_in','<=',$end)
            ->count();

        }
        elseif( $report_month != 0 )
        {
            $report_year = date("Y"); 
         
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $accepted = DB::table('tracking_details')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('tracking_details.date_in','>=',$start)
            ->where('tracking_details.date_in','<=',$end)
            ->count();
        }
        else
        {

        $year = date("Y");
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';

        $all = DB::connection('mysql')->select("CALL allaccept_count('$year', '1')");
        $accepted['first'] = 0;
        foreach ($all as $count)
        {
            $accepted['first'] += $count->accepted;
        }


        $all = DB::connection('mysql')->select("CALL allaccept_count('$year', '2')");
        $accepted['second'] = 0;


        foreach ($all as $count)
        {
            $accepted['second'] += $count->accepted;
        }
        $all = DB::connection('mysql')->select("CALL allaccept_count('$year', '3')");
        $accepted['third'] = 0;
        foreach ($all as $count)
        {
            $accepted['third'] += $count->accepted;
        }


        $all = DB::connection('mysql')->select("CALL allaccept_count('$year', '4')");
        $accepted['fourth'] = 0;
        foreach ($all as $count)
        {
            $accepted['fourth'] += $count->accepted;
        }

            // $accepted['first'] = DB::table('tracking_details')
            // ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            // ->leftJoin('section', 'users.section', '=', 'section.id')
            // ->whereNotNull('tracking_details.status')
            // ->where(DB::raw('QUARTER(tracking_details.date_in)'), 1)
            // ->where(DB::raw('YEAR(tracking_details.date_in)'), '=', $year)
            // ->count();

            // $accepted['second'] = DB::table('tracking_details')
            // ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            // ->leftJoin('section', 'users.section', '=', 'section.id')
            // ->whereNotNull('tracking_details.status')
            // ->where(DB::raw('QUARTER(tracking_details.date_in)'), 2)
            // ->where(DB::raw('YEAR(tracking_details.date_in)'), '=', $year)
            // ->count();

            // $accepted['third'] = DB::table('tracking_details')
            // ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            // ->leftJoin('section', 'users.section', '=', 'section.id')
            // ->whereNotNull('tracking_details.status')
            // ->where(DB::raw('QUARTER(tracking_details.date_in)'), 3)
            // ->where(DB::raw('YEAR(tracking_details.date_in)'), '=', $year)
            // ->count();

            // $accepted['fourth'] = DB::table('tracking_details')
            // ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            // ->leftJoin('section', 'users.section', '=', 'section.id')
            // ->whereNotNull('tracking_details.status')
            // ->where(DB::raw('QUARTER(tracking_details.date_in)'), 4)
            // ->where(DB::raw('YEAR(tracking_details.date_in)'), '=', $year)
            // ->count();
        }


        return $accepted;
      
    }


    static function allcountCycleEnd()
    {
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');
        if($report_year != 0 && $report_month != 0 )
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

        $cycleend1 = DB::connection('mysql')->select("CALL all_cycle_end('$start', '$end','1')");

        $cycleend = count($cycleend1);

        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $cycleend1 = DB::connection('mysql')->select("CALL all_cycle_end('$start', '$end','1')");

            $cycleend = count($cycleend1);

        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
    
            $cycleend1 = DB::connection('mysql')->select("CALL all_cycle_end('$start', '$end','1','$year','1')");

            $cycleend['first'] = count($cycleend1);

            $cycleend2 = DB::connection('mysql')->select("CALL all_cycle_end('$start', '$end','1','$year','2')");

            $cycleend['second'] = count($cycleend2);

            $cycleend3 = DB::connection('mysql')->select("CALL all_cycle_end('$start', '$end','1','$year','3')");

            $cycleend['third'] = count($cycleend3);

            $cycleend4 = DB::connection('mysql')->select("CALL all_cycle_end('$start', '$end','1','$year','4')");

            $cycleend['fourth'] = count($cycleend4);
        }

        return $cycleend;
    }

    static function countCycleEnd1($section){
        $report_year = Session::get('report_year1');
        $report_month = Session::get('report_month1');
        if($report_year != 0 && $report_month != 0 )
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

        $cycleend1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','1')");

        $cycleend = count($cycleend1);

        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $cycleend1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','1')");

            $cycleend = count($cycleend1);

        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
    
            $cycleend1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','1')");

            $cycleend = count($cycleend1);
        }

        return $cycleend;
    }

    static function countCycleEnd($section){
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');
        if($report_year != 0 && $report_month != 0 )
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

        $cycleend1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','1')");

        $cycleend = count($cycleend1);

        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $cycleend1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','1')");

            $cycleend = count($cycleend1);

        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
    
            $cycleend1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','1')");

            $cycleend = count($cycleend1);
        }

        return $cycleend;
    }

    static function countOngoing($section){
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');
        if($report_year != 0 && $report_month != 0)
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

        

        $ongoing = DB::table('vw_ongoing_reported')
        ->where('status',0)
        ->where('prepared_date','>=',$start)
        ->where('prepared_date','<=',$end)
        ->where('sectionid',$section)
        ->count();
        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

           
        $ongoing1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','0')");
                
        $ongoing = count($ongoing1);

        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
    
            $ongoing1 = DB::connection('mysql')->select("CALL chd12_printreport('$start', '$end','$section','0')");
                
            $ongoing = count($ongoing1);

        }

        return $ongoing;
    }

    static function countCreated($section){

        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');
        if($report_year != 0 && $report_month != 0)
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

        $created = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
             ->where('tracking_master.prepared_date','>=',$start)
             ->where('tracking_master.prepared_date','<=',$end)
            ->where('section.id',$section)
            ->count();
        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

             $created = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('tracking_master.prepared_date','>=',$start)
            ->where('tracking_master.prepared_date','<=',$end)
            ->where('section.id',$section)
            ->count();

        }
        
        else{
        $year = date("Y");
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';

         $created = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->leftJoin(\DB::raw('(SELECT route_no,max(id) as maxid from tracking_details group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->leftjoin('tracking_details', 'tracking_details.id', '=', 't2.maxid')
            ->where('tracking_master.prepared_date','>=',$start)
            ->where('tracking_master.prepared_date','<=',$end)
            ->where('section.id',$section)
            ->count();
        }

        return $created;
    }


    static function allcountOngoing($section){
    
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
    
            $ongoing1 = DB::connection('mysql')->select("CALL all_ongoing('$start', '$end','0','$year','1')");
                
            $ongoing['first'] = count($ongoing1);

            $ongoing2 = DB::connection('mysql')->select("CALL all_ongoing('$start', '$end','0','$year','2')");
                
            $ongoing['second'] = count($ongoing2);

            $ongoing3 = DB::connection('mysql')->select("CALL all_ongoing('$start', '$end','0','$year','3')");
                
            $ongoing['third'] = count($ongoing3);

            $ongoing4 = DB::connection('mysql')->select("CALL all_ongoing('$start', '$end','0','$year','4')");
                
            $ongoing['fourth'] = count($ongoing4);


        return $ongoing;
    }

    static function allcountCreated($section){

        $year = date("Y");
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';

             $created['first'] = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->leftJoin(\DB::raw('(SELECT route_no,max(id) as maxid, max(status) as maxstatus from tracking_details group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->leftjoin('tracking_details', 'tracking_details.id', '=', 't2.maxid')
            ->where(DB::raw('QUARTER(tracking_master.prepared_date)'), 1)
            ->whereNotNull('t2.maxstatus')
            ->where(DB::raw('YEAR(tracking_master.prepared_date)'), '=', $year)
            ->count();

            $created['second'] = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->leftJoin(\DB::raw('(SELECT route_no,max(id) as maxid, max(status) as maxstatus from tracking_details group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->leftjoin('tracking_details', 'tracking_details.id', '=', 't2.maxid')
            ->where(DB::raw('QUARTER(tracking_master.prepared_date)'), 2)
            ->whereNotNull('t2.maxstatus')
            ->where(DB::raw('YEAR(tracking_master.prepared_date)'), '=', $year)
            ->count();

            $created['third'] = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->leftJoin(\DB::raw('(SELECT route_no,max(id) as maxid, max(status) as maxstatus from tracking_details group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->leftjoin('tracking_details', 'tracking_details.id', '=', 't2.maxid')
            ->where(DB::raw('QUARTER(tracking_master.prepared_date)'), 3)
            ->whereNotNull('t2.maxstatus')
            ->where(DB::raw('YEAR(tracking_master.prepared_date)'), '=', $year)
            ->count();

            $created['fourth'] = DB::table('tracking_master')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->leftJoin(\DB::raw('(SELECT route_no,max(id) as maxid, max(status) as maxstatus from tracking_details group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->leftjoin('tracking_details', 'tracking_details.id', '=', 't2.maxid')
            ->where(DB::raw('QUARTER(tracking_master.prepared_date)'), 4)
            ->whereNotNull('t2.maxstatus')
            ->where(DB::raw('YEAR(tracking_master.prepared_date)'), '=', $year)
            ->count();
        

        return $created;
    }

    public function allDocuments()
    {
        
        $range = Session::get('range');
        $doc_type = Session::get('doc_type');
        $keyword = Session::get('keywordAll');
        $section = Session::get('section');
        $division = Session::get('division');


        $data['daterange'] = $range;
        $data['doc_type'] = $doc_type;
        $data['keyword'] = $keyword;
        $data['section'] = $section;
        $data['division'] = $division;

        $str = Session::get('range');
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d'.' 12:00:00',strtotime($tmp));

        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d'.' 23:59:00',strtotime($tmp));


        if($doc_type != null && $range != null && $section != null && $division != null)
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->where('prepared_date','>=',$startdate)
            ->where('prepared_date','<=',$enddate)
            ->where('doc_type',$doc_type)
            ->where('users.section',$section)
            ->where('users.division',$division)
                ->orderBy('id','desc')
                ->paginate(10);
        }

        elseif($doc_type != null && $range != null && $division != null) 
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
            $join->on('tracking_master.route_no', '=', 't2.route_no');
             })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->where('prepared_date','>=',$startdate)
            ->where('prepared_date','<=',$enddate)
            ->where('doc_type',$doc_type)
            ->where('users.division',$division)
            ->orderBy('tracking_master.id','desc')
            ->paginate(10);
        }

        elseif($doc_type != null && $section != null && $division != null)
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->where('users.section',$section)
            ->where('doc_type',$doc_type)
            ->where('users.division',$division)
                ->orderBy('id','desc')
                ->paginate(10);
        }

        elseif($section != null && $range != null && $division != null)
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->where('prepared_date','>=',$startdate)
            ->where('prepared_date','<=',$enddate)
            ->where('users.division',$division)
            ->where('users.section',$section)
                ->orderBy('id','desc')
                ->paginate(10);
        }

                
        elseif($doc_type != null && $range != null) 
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
            $join->on('tracking_master.route_no', '=', 't2.route_no');
             })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->where('prepared_date','>=',$startdate)
            ->where('prepared_date','<=',$enddate)
            ->where('doc_type',$doc_type)
            ->orderBy('tracking_master.id','desc')
            ->paginate(10);
        }
        
        
        elseif($range != null && $division != null)
        {

            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status','users.division as division')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('tracking_master.description','like',"%$keyword%")
                    ->orWhere('tracking_master.purpose','like',"%$keyword%");
            })
            ->where('tracking_master.prepared_date','>=',$startdate)
            ->where('tracking_master.prepared_date','<=',$enddate)
            ->where('users.division',$division)
                ->orderBy('tracking_master.id','desc')
                ->paginate(10);
        }
     
        elseif($range != null)
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('tracking_master.description','like',"%$keyword%")
                    ->orWhere('tracking_master.purpose','like',"%$keyword%");
            })
            ->where('tracking_master.prepared_date','>=',$startdate)
            ->where('tracking_master.prepared_date','<=',$enddate)
                ->orderBy('tracking_master.id','desc')
                ->paginate(10);
        }


        elseif($section != null && $division != null)
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.route_no')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('tracking_master.description','like',"%$keyword%")
                    ->orWhere('tracking_master.purpose','like',"%$keyword%");
                    
            })
            ->where('tracking_master.prepared_date','>=',$startdate)
            ->where('tracking_master.prepared_date','<=',$enddate)
            ->where('users.section',$section)
            ->where('users.division',$division)
                ->orderBy('tracking_master.id','desc')
                ->paginate(10);
        }
      elseif($doc_type != null && $division != null)
        {
            $data['documents'] = DB::table('tracking_master')
            ->select('tracking_master.*','t2.status as status')
            ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                $join->on('tracking_master.route_no', '=', 't2.route_no');
                 })
            ->where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('tracking_master.description','like',"%$keyword%")
                    ->orWhere('tracking_master.purpose','like',"%$keyword%");
                    
            })
            ->where('users.section',$section)
            ->where('users.division',$division)
                ->orderBy('tracking_master.id','desc')
                ->paginate(10);
        }
        elseif($doc_type == 'ALL')
        {
            $data['documents'] = Tracking::where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            
                ->orderBy('id','desc')
                ->paginate(10);
        }
        elseif($doc_type != null)
        {
            $data['documents'] = Tracking::where(function($q) use ($keyword){
                $q->where('tracking_master.route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->where('doc_type',$doc_type)
                ->orderBy('id','desc')
                ->paginate(10);
        }

        else{
    
        $data['documents'] = Tracking::where(function($q) use ($keyword){
            $q->where('tracking_master.route_no','like',"%$keyword%")
                ->orwhere('description','like',"%$keyword%")
                ->orWhere('purpose','like',"%$keyword%");
        })
            ->orderBy('id','desc')
            ->paginate(10);
        }
        $data['access'] = $this->middleware('access');
        return view('document.all',$data);
    }

    public function searchDocuments(Request $request){
        Session::put('keywordAll',$request->keyword);
        Session::put('range',$request->daterange);
        Session::put('doc_type',$request->doc_type);
        Session::put('section',$request->section);
        Session::put('division',$request->division);
        return self::allDocuments();
    }

    public function ongoingKeyword(Request $req)
    {
        $keyword1 = $req->keyword;
        $keyword = '%'.$keyword1.'%';
        Session::put('on_keyword',$keyword1);
        $id = $req->sec_id;
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');

        if($report_year != 0 && $report_month != 0)
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $ongoing = DB::connection('mysql')->select("CALL chd12_search('$start','$end','$id','0','$keyword')");
            
        // $ongoing = DB::table('tracking_master')
        // ->select('select tracking_details.*,tracking_master.prepared_by, tracking_master.description')
        // ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
        // ->leftJoin('section', 'users.section', '=', 'section.id')
        // ->join('(select route_no,max(id) as maxid from tracking_details group by route_no) s on s.route_no = tracking_master.route_no')
        // ->where('tracking_details.status',0)
        // ->where('tracking_master.prepared_date','>=',$start)
        // ->where('tracking_master.prepared_date','<=',$end)
        // ->where('section.id',$section)
        // ->orderby('tracking_details.id','desc')
        // ->count();

        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $ongoing = DB::connection('mysql')->select("CALL chd12_search('$start', '$end','$id','0','$keyword')");
            
        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
            $ongoing = DB::connection('mysql')->select("CALL chd12_search('$start', '$end','$id','0','$keyword')");
        }

        return view('report.ongoing_body',[
            'ongoing' => $ongoing,
            'id' => $id
        ]);

    }

    public function ongoingBody($id)
    {
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');

        if($report_year != 0 && $report_month != 0)
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $ongoing = DB::table('vw_ongoing_reported')
            ->where('status',0)
            ->where('prepared_date','>=',$start)
            ->where('prepared_date','<=',$end)
            ->where('sectionid',$id)
            ->orderby('id')
            ->get();
            
        // $ongoing = DB::table('tracking_master')
        // ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
        // ->leftJoin('section', 'users.section', '=', 'section.id')
        // ->leftJoin('tracking_details', 'tracking_master.route_no', '=', 'tracking_details.route_no')
        // ->where('tracking_details.status',0)
        // ->where('tracking_master.prepared_date','>=',$start)
        // ->where('tracking_master.prepared_date','<=',$end)
        // ->where('section.id',$section)
        // ->orderby('tracking_details.id','desc')
        // ->count();
        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $ongoing = DB::table('vw_ongoing_reported')
            ->where('status',0)
            ->where('prepared_date','>=',$start)
            ->where('prepared_date','<=',$end)
            ->where('sectionid',$id)
            ->orderby('id')
            ->get();
            
        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
           
                        
            $ongoing = DB::table('vw_ongoing_reported')
            ->where('status',0)
            ->where('prepared_date','>=',$start)
            ->where('prepared_date','<=',$end)
            ->where('sectionid',$id)
            ->orderby('id')
            ->get();

        }

        return view('report.ongoing_body',[
            'ongoing' => $ongoing,
            'id' => $id
        ]);
    }
}