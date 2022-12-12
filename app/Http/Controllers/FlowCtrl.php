<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Tracking_Releasev2;
use App\Tracking;
use App\Section;
use App\Tracking_Details;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Users;

class FlowCtrl extends Controller
{
    public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }

    public function index(Request $req)
    {
        $user = Session::get('auth');

        if($req->daterange)
        {
            $daterange = $req->daterange;
        }
        else
        {
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
            $daterange = "01/01/$year - 12/31/$year";
        }
        if($req->doc_type)
        {
            $doc_type = $req->doc_type;
        }
        else
        {
            $doc_type = "PR_ITSUP";
        }
        if($req->keyword)
        {
            $keyword = $req->keyword;
        }
        else
        {
            $keyword = "";
        }
        if($req->section)
        {
            $section = $req->section;
        }
        else
        {
            $section = "";
        }

        if( 
            ($doc_type == "PR_DRUG" || $doc_type == "PR_CATERING") || ($doc_type == "PR_VAN" || $doc_type == "PR_MEDSUP") || ($doc_type == "PR_MEDEQ" || $doc_type == "PR_ITSUP") ||
            ($doc_type == "PR_OFFSUP" || $doc_type == "PR_VEHREQM") || ($doc_type == "PR_SECURITY" || $doc_type == "PR_SOFTWARE") || $doc_type == "PR_COLAT"
        )
        {  

        $str = $daterange;
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d'.' 12:00:00',strtotime($tmp));
    
        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d'.' 23:59:00',strtotime($tmp));

        if($keyword != null && $section != null)
        {
            $document = Tracking::select('tracking_details.*','tracking_master.doc_type')
            ->leftJoin('tracking_details', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->leftjoin('users','tracking_master.prepared_by','=','users.id')
            ->where('users.section',$section)
            ->where('tracking_master.doc_type',$doc_type)
            ->where(function($q) use ($keyword){
                $q->where('tracking_details.route_no','like',"%$keyword%")
                    ->orwhere('tracking_master.description','like',"%$keyword%");
            })
            ->where('tracking_master.created_at','>=',$startdate)
            ->where('tracking_master.created_at','<=',$enddate)
            ->get();
        }
        if($section)
        {
            $document = Tracking::select('tracking_details.*','tracking_master.doc_type')
            ->leftJoin('tracking_details', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->leftjoin('users','tracking_master.prepared_by','=','users.id')
            ->where('users.section',$section)
            ->where('tracking_master.doc_type',$doc_type)
            ->where('tracking_master.created_at','>=',$startdate)
            ->where('tracking_master.created_at','<=',$enddate)
            ->get();
        }
        if($keyword)
        {
            $document = Tracking::select('tracking_details.*','tracking_master.doc_type')
            ->leftJoin('tracking_details', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->leftjoin('users','tracking_master.prepared_by','=','users.id')
            ->where('tracking_master.doc_type',$doc_type)
            ->where(function($q) use ($keyword){
                $q->where('tracking_details.route_no','like',"%$keyword%")
                    ->orwhere('tracking_master.description','like',"%$keyword%");
            })
            ->where('tracking_master.created_at','>=',$startdate)
            ->where('tracking_master.created_at','<=',$enddate)
            ->get();
        }
        else{
            $document = Tracking::select('tracking_details.*','tracking_master.doc_type')
            ->leftJoin('tracking_details', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->leftjoin('users','tracking_master.prepared_by','=','users.id')
            ->where('tracking_master.doc_type',$doc_type)
            ->where('tracking_master.created_at','>=',$startdate)
            ->where('tracking_master.created_at','<=',$enddate)
            ->get();
        }
        
        

        

        if(count($document) > 0)
            {
                foreach($document as $doc)
                {
                $data['route_no'][] = $doc->route_no;
                $data['doc_type'][] = $doc->doc_type;
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
                            $data['released_section_to_id'][] = $released_section_to->id;
                        } else {
                            $data['released_section_to'][] = "No Data";
                        }
                        $data['released_date_time'][] = $released->released_date;
                        $data['released_duration_status'][] = $released->rel_status;
                        $data['released_date'][] = date('M d, Y', strtotime($released->released_date));
                        $data['released_time'][] = date('h:i A', strtotime($released->released_date));
                        $data['released_remarks'][] = $released->remarks;
                        }else {
                        $data['released_alert'][]  = "";
                        $data['released_section_to'][] = "";
                        $data['released_section_to_id'][] = "";
                        $data['released_date_time'][] = "";
                        $data['released_date'][] = "";
                        $data['released_time'][] = "";
                        $data['released_remarks'][] = "";
                        $data['released_status'][] = "";
                        $data['released_duration_status'][] = "";
                    }

                    
                }
                }
                for($i=0;$i<count($data['id']);$i++)
                {
                    // print_r($data['route_no'][$i]);
                    // print_r('<br>');
                    // print_r($data['released_section_to'][$i]);
                    // print_r('<br>');
                    // print_r($data['doc_type'][$i]);
                    // print_r('<br>');
                    // print_r($data['released_section_to_id'][$i]);
                    // print_r('<br>');
                    $bypass[] = "";
                    $bypass_section[] = "";

                    // if( ($data['released_section_to_id'][$i] == 99 || $data['released_section_to_id'][$i] == 73) || ($data['released_section_to_id'][$i] == 90 
                    // || $data['released_section_to_id'][$i] == 83) || ($data['released_section_to_id'][$i] == 95 || $data['released_section_to_id'][$i] == 82)
                    //  || $data['released_section_to_id'][$i] == "") 
                    // {
                    //     continue;
                    // }
                    // else{
                    //  $bypass[] = $data['route_no'][$i];
                    // }

                    if($data['released_section_to_id'][0] == 99 || $data['released_section_to_id'][0] == 113)
                    {
                        
                    }
                    else{
                        $bypass[] = $data['route_no'][$i];
                        $bypass_section[] = $data['released_section_to_id'][$i];
                    }

                    if($data['released_section_to_id'][1] == 73)
                    {
                        
                    }
                    else{
                        $bypass[] = $data['route_no'][$i];
                        $bypass_section[] = $data['released_section_to_id'][$i];
                    }

                    if($data['released_section_to_id'][2] == 90)
                    {
                        
                    }
                    else{
                        $bypass[] = $data['route_no'][$i];
                        $bypass_section[] = $data['released_section_to_id'][$i];
                    }


                    if($data['released_section_to_id'][3] == 83)
                    {
                        
                    }
                    else{
                        $bypass[] = $data['route_no'][$i];
                        $bypass_section[] = $data['released_section_to_id'][$i];
                    }


                    if($data['released_section_to_id'][4] == 95)
                    {
                        
                    }
                    else{
                        $bypass[] = $data['route_no'][$i];
                        $bypass_section[] = $data['released_section_to_id'][$i];
                    }


                    if($data['released_section_to_id'][5] == 82)
                    {
                        
                    }
                    if($data['released_section_to_id'][6] == "")
                    {
                        
                    }
                    else{
                        $bypass[] = $data['route_no'][$i];
                        $bypass_section[] = $data['released_section_to_id'][$i];
                    }


                    }
                
                    $u_bypass = array_unique($bypass);
                    $arr = [];
                    foreach($u_bypass as $u)
                    {
                    $data = DB::table('tracking_master')
                    ->select('tracking_master.*','t2.status as status')
                    ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
                    ->leftJoin(\DB::raw('(SELECT route_no, max(status) as status, max(id) as maxid FROM tracking_details A group by route_no) AS t2'), function($join) {
                        $join->on('tracking_master.route_no', '=', 't2.route_no');
                        })
                    ->where('tracking_master.route_no','=',$u)->first();
                    array_push($arr, $data);
                    }
                    // $info = $data->get();
                    $data = array_filter($arr);
                    // $data = array_slice( $data, 10, 10 );
                }

                else{
                    $data = array();
                }
        }
        else{
            $str = $daterange;
            $data = array();
        }
        
        
            return view('document.bypass',[
                'documents' => $data,
                'section' => $section,
                'daterange' => $str,
                'keyword' => $keyword,
                'doc_type' => $doc_type
            ]);
        
    }

    public function MyPagination($list,$perPage,Request $request)
    {
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($list);

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());

        return $paginatedItems;
    }
}
