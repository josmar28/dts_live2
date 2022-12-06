<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 12/8/2016
 * Time: 8:26 AM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrintLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function printTrack(){
        $display = view("pdf.track");
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($display);
        return $pdf->stream();
    }


    function printLogs(Request $request, $doc_type) {
        if($doc_type =='SAL' || $doc_type=='TEV'){
            $display = view("logs.salary");
        }else if($doc_type =='BILLS'){
            $display = view("logs.bills");
        }else if($doc_type=='PO'){
            $display = view('logs.PurchaseOrder');
        }else if($doc_type=="PRC"){
            $display = view('logs.PurchaseRequestCA');
        }else if($doc_type=="PRR"){
            $display = view('logs.PurchaseRequestR');
        }else if($doc_type=='ALL'){
            $display = view("logs.all");
        } else if($doc_type == 'ROUTE') {
            $display = view('logs.routing_slip');
        } else if($doc_type == 'APP_LEAVE'){
            $display = view('logs.app_leave');
        } else if($doc_type == 'INCOMING'){
            $display = view('logs.incoming');
        } else if($doc_type == 'OFFICE_ORDER'){
            $display = view('logs.office_order');
        } else if($doc_type == 'WORKSHEET') {
            $display = view('logs.general');
        } else if($doc_type == 'JUST_LETTER') {
            $display = view('logs.just_letter');
        } else if($doc_type == 'GENERAL'){
            $display = view('logs.general');
        }else{
            return redirect('document/delivered');
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($display)->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function sectionLogs()
    {
        $keyword = Session::get('sectionLogs');
        $doc_type = $keyword['doc_type'];
        $section = Session::get('auth')->section;
        $keywordSectionLogs = $keyword['keywordSectionLogs'];
        $str = $keyword['str'];
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d H:i:s',strtotime($tmp));

        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d H:i:s',strtotime($tmp));

        if($doc_type!='ALL'){
            $data = DB::table('tracking_details')
                ->select('tracking_details.id as tracking_id','tracking_master.route_no','tracking_master.description','tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by')
                ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                ->leftJoin('section', 'users.section', '=', 'section.id')
                ->where(function($q) use ($keywordSectionLogs){
                    $q->where('tracking_details.route_no','like',"%$keywordSectionLogs%")
                        ->orwhere('tracking_master.description','like',"%$keywordSectionLogs%");
                })
                ->where('doc_type',$doc_type)
                ->where('section.id',$section)
                ->where('date_in','>=',$startdate)
                ->where('date_in','<=',$enddate)
                ->orderBy('date_in','desc');
            $documents = $data->get();

        }else{
            $data = DB::table('tracking_details')
                ->select('tracking_details.id as tracking_id','tracking_master.route_no','tracking_master.description','tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by','tracking_details.action')
                ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                ->leftJoin('section', 'users.section', '=', 'section.id')
                ->where(function($q) use ($keywordSectionLogs){
                    $q->where('tracking_details.route_no','like',"%$keywordSectionLogs%")
                        ->orwhere('tracking_master.description','like',"%$keywordSectionLogs%");
                })
                ->where('section.id',$section)
                ->where('date_in','>=',$startdate)
                ->where('date_in','<=',$enddate)
                ->orderBy('date_in','desc');
            //$documents = $data->skip(2000)->take(1000)->get();
            $documents = $data->get();
        }

        return view('report.sectionLogs',['documents' => $documents]);
    }

    public function sectionTracking($sectionId,$year,$month){
        $sectionTracking = \DB::connection('mysql')->select("call sectionTracking($sectionId,$year,$month)");

        return view('report.sectionTracking',[
            "data" => $sectionTracking,
            "sectionId" => $sectionId,
            "year" => $year
        ]);
    }

}