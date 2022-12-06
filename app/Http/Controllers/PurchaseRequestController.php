<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Tracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Tracking_Details;
use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Users;
use App\Section;
use App\Division;
use App\Designation;
use App\Calendar;
use App\prr_supply;
use App\prr_supply_logs;
use App\prr_meal;
use App\prr_meal_logs;
use App\Http\Controllers\SystemController as System;

class PurchaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function prCashAdvance()
    {
        return view('form.prCashAdvance');
    }

    public function prr_supply_append()
    {
        return view('prr_supply.prr_supply_append');
    }

    public function savePrCashAdvance(Request $request)
    {
        $route_no = date('Y-') . $request->user()->id . date('mdHis');
        $prepared_date = date('Y-m-d H:i:s');

        //ADD TRACKING MASTER
        $tracking = new Tracking();
        $tracking->route_no = $route_no;
        $tracking->doc_type = $request->get('doc_type');
        $tracking->prepared_date = $prepared_date;
        $tracking->prepared_by = $request->get('prepared_by');
        $tracking->amount = $request->get('amount');
        $tracking->description = $request->get('description');
        $tracking->source_fund = $request->get('charge_to');
        $tracking->requested_by = $request->get('requested_by');
        $tracking->save();

        //ADD TRACKING DETAILS
        $q = new Tracking_Details();
        $q->route_no = $route_no;
        $q->date_in = $prepared_date;
        $q->received_by = $request->get('prepared_by');
        $q->delivered_by = $request->get('prepared_by');
        $q->action = $request->get('description');
        $q->save();

        return redirect("/document");
    }

    public function prr_supply_form()
    {
        $requestedBy = Users::select(DB::raw("CONCAT(req.fname,' ',req.mname,' ',req.lname) as fullname"),"req.id")
            ->leftJoin("Section as sec","sec.id","=","users.section")
            ->leftJoin("Users as req","req.id","=","sec.head")
            ->where("users.id","=",Session::get('auth')->id)
            ->first();

        $recommendingApproval = Users::select(DB::raw("CONCAT(rec.fname,' ',rec.mname,' ',rec.lname) as fullname"),"rec.id")
            ->leftJoin("Division as div","div.id","=","users.division")
            ->leftJoin("Users as rec","rec.id","=","div.head")
            ->where("users.id","=",Session::get('auth')->id)
            ->first();

        $headSection = Section::select(DB::raw("CONCAT(u.fname,' ',u.mname,' ',u.lname) as fullname"),"u.id")
            ->leftJoin("users as u","u.id","=","section.head")
            ->where("u.id","!=",$requestedBy->id)
            ->get();

        $headDivision = Division::select(DB::raw("CONCAT(u.fname,' ',u.mname,' ',u.lname) as fullname"),"u.id")
            ->leftJoin("users as u","u.id","=","division.head")
            ->where("u.id","!=",$recommendingApproval->id)
            ->get();

        return view('prr_supply.prr_supply_form',[
                'headSection' => $headSection,
                'headDivision' => $headDivision,
                "requestedBy" => $requestedBy,
                'recommendingApproval' => $recommendingApproval
            ]
        );
    }

    public function prr_supply_info($route_no)
    {
        Session::put('route_no',$route_no);
        $tracking = Tracking::
                select('tracking_master.*','users.*','division.description as div_description','section.description as sec_description')
                ->leftJoin('users','tracking_master.prepared_by','=','users.id')
                ->leftJoin('division','division.id','=','users.division')
                ->leftJoin('section','section.id','=','users.section')
                ->where('route_no',$route_no)
                ->first();

        $requestedBy = Users::select(DB::raw("CONCAT(req.fname,' ',req.mname,' ',req.lname) as fullname"),"req.id")
            ->leftJoin("Section as sec","sec.id","=","users.section")
            ->leftJoin("Users as req","req.id","=","sec.head")
            ->where("users.id","=",$tracking->prepared_by)
            ->first();

        $recommendingApproval = Users::select(DB::raw("CONCAT(rec.fname,' ',rec.mname,' ',rec.lname) as fullname"),"rec.id")
            ->leftJoin("Division as div","div.id","=","users.division")
            ->leftJoin("Users as rec","rec.id","=","div.head")
            ->where("users.id","=",$tracking->division_head)
            ->first();

        $headSection = Section::select(DB::raw("CONCAT(u.fname,' ',u.mname,' ',u.lname) as fullname"),"u.id")
            ->leftJoin("users as u","u.id","=","section.head")
            ->where("u.id","!=",$requestedBy->id)
            ->get();

        $headDivision = Division::select(DB::raw("CONCAT(u.fname,' ',u.mname,' ',u.lname) as fullname"),"u.id")
            ->leftJoin("users as u","u.id","=","division.head")
            ->where("u.id","!=",$recommendingApproval->id)
            ->get();

        $prr_logs = prr_supply_logs::where('route_no',$route_no)
            ->where('status',1)
            ->first()
            ->prr_logs_key;

        $item = prr_supply::where('route_no','=',$route_no)
            ->where('status',1)
            ->where('prr_logs_key',$prr_logs)
            ->get();

        $routed = Tracking_Details::where('route_no',$route_no)
            ->count();

        $paperSize = view('prr_supply.paperSize');
        return view('prr_supply.prr_supply_info',[
            'route_no' => $route_no,
            'headSection' => $headSection,
            'headDivision' => $headDivision,
            "requestedBy" => $requestedBy,
            'recommendingApproval' => $recommendingApproval,
            'tracking' => $tracking,
            'routed' => $routed,
            'prr_logs' => $prr_logs,
            'item' => $item,
            'paperSize' => $paperSize
        ]);
    }

    public function prr_supply_update(Request $request)
    {
        $route_no = $request->get('route_no');
        //UPDATE PRR SUPPLY TABLE
        prr_supply::where("route_no",$route_no)
            ->update(['status' => 0]); //delete the previous item
        //UPDATE STATUS IN PRR SUPPLY LOGS
        prr_supply_logs::where("route_no",$route_no)
            ->update(['status' => 0]); //delete the previous item logs

        //ADD PRR_LOGS
        $updated_date = date('Y-m-d H:i:s');
        $prr_logs_key = "logs".date('Y-') . $request->get('prepared_by') . date('mdHis');

        $prr_logs = new prr_supply_logs();
        $prr_logs->prr_logs_key = $prr_logs_key;
        $prr_logs->route_no = $route_no;
        $prr_logs->updated_date = $updated_date;
        $prr_logs->updated_by = Session::get('auth')->id; //mas maayo na user na ge create mabutang sa logs
        $prr_logs->status = 1;
        $prr_logs->save();

        //ADD ANOTHER IN PRR TABLE
        $count = 0;
        foreach($request->get('qty') as $pr){
            if($request->get('issue')[$count] && $request->get('description')[$count] && $request->get('unit_cost')[$count] && $request->get('estimated_cost')[$count]) {
                $pr = new prr_supply();
                $pr->route_no = $route_no;
                $pr->prr_logs_key = $prr_logs_key;
                $pr->qty = $request->get('qty')[$count];
                $pr->issue = $request->get("issue")[$count];
                $pr->description = $request->get("description")[$count];
                $pr->specification = $request->get("specification")[$count];
                $pr->unit_cost = $request->get("unit_cost")[$count];
                $pr->estimated_cost = $request->get("estimated_cost")[$count];
                $pr->status = 1;
                $pr->save();
            }
            $count++;
        }

        //UPDATE TRACKING MASTER
        $prepared_date = $request->get('pr_date');
        $prepared_date =  substr($prepared_date,6,4).'-'.substr($prepared_date,0,2).'-'.substr($prepared_date,3,2).' '.date('H:i:s');
        Tracking::where('route_no',$route_no)->update([
            "prepared_date" => $prepared_date,
            "purpose" => $request->get('purpose'),
            "source_fund" => $request->get('charge_to'),
            "requested_by" => $request->get('requested_by'),
            "cash_advance_of" => $request->get('cash_advance_of'),
            "pr_date" => $request->get('pr_date'),
        ]);

        System::logDefault('Updated',$route_no);
        Session::put('updated',true);
        return redirect()->back();
    }

    public function prr_supply_post(Request $request)
    {
        $prepared_date = $request->get('pr_date');
        $prepared_date =  substr($prepared_date,6,4).'-'.substr($prepared_date,0,2).'-'.substr($prepared_date,3,2).' '.date('H:i:s');
        $route_no = date('Y-') . $request->user()->id . date('mdHis');
        Session::put('route_no', $route_no);

        //ADD PRR_LOGS
        $updated_date = date('Y-m-d H:i:s');
        $prr_logs_key = "logs".date('Y-') . $request->user()->id . date('mdHis');
        $prr_logs = new prr_supply_logs();
        $prr_logs->prr_logs_key = $prr_logs_key;
        $prr_logs->route_no = $route_no;
        $prr_logs->updated_date = $updated_date;
        $prr_logs->updated_by = Session::get('auth')->id;
        $prr_logs->status = 1;
        $prr_logs->save();

        //ADD PRR TABLE
        $count = 0;
        foreach($request->get('qty') as $pr){
            if($request->get('issue')[$count] && $request->get('description')[$count] && $request->get('unit_cost')[$count] && $request->get('estimated_cost')[$count]) {
                $pr = new prr_supply();
                $pr->route_no = $route_no;
                $pr->prr_logs_key = $prr_logs_key;
                $pr->qty = $request->get('qty')[$count];
                $pr->issue = $request->get("issue")[$count];
                $pr->description = $request->get("description")[$count];
                $pr->specification = $request->get("specification")[$count];
                $pr->unit_cost = $request->get("unit_cost")[$count];
                $pr->estimated_cost = $request->get("estimated_cost")[$count];
                $pr->status = 1;
                $pr->save();
            }
            $count++;
        }

        //ADD TRACKING MASTER
        $tracking = new Tracking();
        $tracking->route_no = $route_no;
        $tracking->doc_type = $request->get('doc_type');
        $tracking->prepared_date = $prepared_date;
        $tracking->prepared_by = Session::get('auth')->id;
        $tracking->division_head = $request->get('division_head');
        $tracking->amount = $request->get('amount');
        $tracking->purpose = $request->get('purpose');
        $tracking->source_fund = $request->get('charge_to');
        $tracking->requested_by = $request->get('requested_by');
        $tracking->cash_advance_of = $request->get('cash_advance_of');
        $tracking->pr_date = $prepared_date;
        $tracking->save();

        //ADD TRACKING DETAILS
        $q = new Tracking_Details();
        $q->route_no = $route_no;
        $q->date_in = $prepared_date;
        $q->received_by = Session::get('auth')->id;
        $q->delivered_by = Session::get('auth')->id;
        $q->action = $request->get('purpose');
        $q->save();

        Session::put('added',true);
        return redirect("/document");
    }

    public function getDesignation($id)
    {
        $designation = Users::leftJoin("Designation","designation.id","=","users.designation")
            ->where("users.id","=",$id)
            ->first()
            ->description;
        return $designation;
    }

    public function prr_supply_pdf($paperSize = null)
    {
        $prr_logs = prr_supply_logs::where('route_no',Session::get('route_no'))
                            ->where('status',1)
                            ->first()
                            ->prr_logs_key;
        $meal = prr_supply::where('route_no','=',Session::get('route_no'))
                            ->where('status',1)
                            ->where('prr_logs_key',$prr_logs)
                            ->get();

        $tracking = Tracking::where('route_no',Session::get('route_no'))->first();
        $user = Users::where('id',$tracking->prepared_by)->first();
        $section = Section::where('id',$user->section)->first();
        $division = Division::where('id',$user->division)->first();

        $display = view("prr_supply.prr_supply_pdf",['meal' => $meal,'tracking' => $tracking,'user' => $user,'section' => $section,'division' => $division]);
        $pdf = App::make('dompdf.wrapper');
        /*$pdf->loadHTML($display)->setPaper('a4', 'landscape');*/
        $pdf->loadHTML($display)->setPaper($paperSize,'portrait');

        return $pdf->stream();
    }

    public function calendar(Request $request)
    {
        $calendar = new Calendar();
        $calendar->title = $request->get('title');
        $calendar->start = $request->get('start');
        $calendar->backgroundColor = $request->get('background_color');
        $calendar->borderColor = $request->get('border_color');
        $calendar->save();

        return redirect('/calendar');
    }

    public function prr_supply_page()
    {
        
        $prr_logs = prr_supply_logs::where('route_no',Session::get('route_no'))
                    ->where('status',1)
                    ->first()
                    ->prr_logs_key;
        $item = prr_supply::where('route_no','=',Session::get('route_no'))
                                    ->where('status',1)
                                    ->where('prr_logs_key',$prr_logs)
                                    ->get();

        $tracking = Tracking::where('route_no',Session::get('route_no'))->first();

        $section = Section::all();
        foreach($section as $row){
            $user = Users::where('id','=',$row->head)->first();
            $section_head[] = $user;
        }
        $division = Division::all();
        foreach($division as $row){
            $user = Users::where('id','=',$row->head)->first();
            $division_head[] = $user;
        }
        return view('prr_supply.prr_supply_page',['section_head' => $section_head, 'division_head' => $division_head,'item' => $item,'tracking' => $tracking]);
    }

    public function prr_supply_history()
    {
        $route_no = Session::get('route_no');

        $tracking = Tracking::where('route_no','=',Session::get('route_no'))->first();
        $user = Users::where('id','=',$tracking->prepared_by)->first();
        $section = Section::where('id','=',$user->section)->first();
        $division = Division::where('id','=',$user->division)->first();
        $prr_logs = prr_supply_logs::where("route_no",$route_no)
                            ->where('status',0)
                            ->get();

        return view("prr_supply.prr_supply_history",['tracking' => $tracking,'user' => $user,'section' => $section,'division' => $division,"prr_logs" => $prr_logs]);
    }

    public function prr_supply_remove(){
        $route_no = Session::get('route_no');
        Tracking::where('route_no',$route_no)->first()->delete();
        Tracking_Details::where('route_no',$route_no)->first()->delete();
        $prr = prr_supply::where('route_no',$route_no)->get();
        foreach($prr as $row){
            $row->delete();
        }
        Session::put('deletedPR',true);
        System::logDefault('Deleted',$route_no);
        return redirect()->back();
    }

    /// PRR MEAL
    public function prr_meal_form()
    {
        $section = Section::all();
        foreach($section as $row){
            $user = Users::where('id',$row->head)->first();
            $section_head[] = $user;
        }
        $division = Division::all();
        foreach($division as $row){
            $user = Users::where('id',$row->head)->first();
            $division_head[] = $user;
        }
        return view('prr_meal.prr_meal_form',['section_head' => $section_head, 'division_head' => $division_head]);
    }

    public function prr_meal_append()
    {
        return view('prr_meal.prr_meal_append');
    }

    public function prr_meal_post(Request $request)
    {
        $prepared_date = $request->get("prepared_date");
        $prepared_date =  substr($prepared_date,6,4).'-'.substr($prepared_date,0,2).'-'.substr($prepared_date,3,2).' '.date('H:i:s');
        $route_no = date('Y-') . $request->user()->id . date('mdHis');
        Session::put('route_no', $route_no);

        //ADD PRR_LOGS MEAL
        $updated_date = date('Y-m-d H:i:s');
        $prr_logs_key = "logs".date('Y-') . $request->user()->id . date('mdHis');
        $prr_logs = new prr_meal_logs();
        $prr_logs->route_no = $route_no;
        $prr_logs->prr_logs_key = $prr_logs_key;
        $prr_logs->global_title = $request->get('global_title');
        $prr_logs->updated_date = $updated_date;
        $prr_logs->updated_by = Session::get('auth')->id;
        $prr_logs->status = 1;
        $prr_logs->save();

        //ADD PRR TABLE MEAL
        $count = 0;
        foreach($request->get('expected') as $pr)
        {
            if($request->get('description')[$count] && $request->get('expected')[$count] && $request->get('date_time')[$count] && $request->get('unit_cost')[$count] && $request->get('estimated_cost')[$count] != '') {
                $pr = new prr_meal();
                $pr->route_no = $route_no;
                $pr->prr_logs_key = $prr_logs_key;
                $pr->description = $request->get("description")[$count];
                $pr->expected = $request->get("expected")[$count];
                $pr->date_time = $request->get("date_time")[$count];
                $pr->unit_cost = $request->get("unit_cost")[$count];
                $pr->estimated_cost = $request->get("estimated_cost")[$count];
                $pr->status = 1;
                $pr->save();
            }
            $count++;
        }

        //ADD TRACKING MASTER
        $tracking = new Tracking();
        $tracking->route_no = $route_no;
        $tracking->doc_type = $request->get('doc_type');
        $tracking->prepared_date = $prepared_date;
        $tracking->prepared_by = $request->get('prepared_by');
        $tracking->division_head = $request->get('division_head');
        $tracking->amount = $request->get('amount');
        $tracking->purpose = $request->get('purpose');
        $tracking->source_fund = $request->get('charge_to');
        $tracking->requested_by = $request->get('requested_by');
        $tracking->save();

        //ADD TRACKING DETAILS
        $q = new Tracking_Details();
        $q->route_no = $route_no;
        $q->date_in = $prepared_date;
        $q->received_by = $request->get('prepared_by');
        $q->delivered_by = $request->get('prepared_by');
        $q->action = $request->get('purpose');
        $q->save();

        Session::put('added',true);

        return redirect("/document");
        /*$count = 0;
        foreach($request->get('category') as $meal)
        {
            echo json_encode($meal);
        }*/
    }

    public function prr_meal_page()
    {
        $prr_meal_logs = prr_meal_logs::where('route_no',Session::get('route_no'))
                            ->where('status',1)
                            ->first();
        $meal = prr_meal::where('route_no','=',Session::get('route_no'))
                            ->where('status',1)
                            ->where('prr_logs_key',$prr_meal_logs->prr_logs_key)
                            ->get();

        $tracking = Tracking::where('route_no',Session::get('route_no'))->first();

        $section = Section::all();
        foreach($section as $row){
            $user = Users::where('id','=',$row->head)->first();
            $section_head[] = $user;
        }
        $division = Division::all();
        foreach($division as $row){
            $user = Users::where('id','=',$row->head)->first();
            $division_head[] = $user;
        }
        return view('prr_meal.prr_meal_page',['section_head' => $section_head, 'division_head' => $division_head,'meal' => $meal,'tracking' => $tracking,'prr_meal_logs' => $prr_meal_logs]);
    }

    public function prr_meal_history()
    {
        $route_no = Session::get('route_no');

        $tracking = Tracking::where('route_no',Session::get('route_no'))->first();
        $user = Users::where('id',$tracking->prepared_by)->first();
        $section = Section::where('id',$user->section)->first();
        $division = Division::where('id',$user->division)->first();
        $prr_meal_logs = prr_meal_logs::where("route_no",$route_no)
            ->where('status',0)
            ->get();

        return view("prr_meal.prr_meal_history",['tracking' => $tracking,'user' => $user,'section' => $section,'division' => $division,"prr_meal_logs" => $prr_meal_logs]);
    }

    public function prr_meal_update(Request $request)
    {
        $route_no = Session::get('route_no');

        //UPDATE PRR MEAL TABLE
        prr_meal::where("route_no",$route_no)
            ->update(['status' => 0]);
        //UPDATE STATUS IN PRR MEAL LOGS
        prr_meal_logs::where("route_no",$route_no)
            ->update(['status' => 0]);

        //ADD PRR MEAL LOGS
        $updated_date = date('Y-m-d H:i:s');
        $prr_logs_key = "logs".date('Y-') . $request->user()->id . date('mdHis');

        $prr_logs = new prr_meal_logs();
        $prr_logs->prr_logs_key = $prr_logs_key;
        $prr_logs->global_title = $request->get('global_title');
        $prr_logs->route_no = $route_no;
        $prr_logs->updated_date = $updated_date;
        $prr_logs->updated_by = Session::get('auth')->id;
        $prr_logs->status = 1;
        $prr_logs->save();

        //ADD ANOTHER PRR TABLE MEAL
        $count = 0;
        foreach($request->get('expected') as $pr)
        {
            if($request->get('description')[$count] && $request->get('expected')[$count] && $request->get('date_time')[$count] && $request->get('unit_cost')[$count] && $request->get('estimated_cost')[$count] != '') {
                $pr = new prr_meal();
                $pr->route_no = $route_no;
                $pr->prr_logs_key = $prr_logs_key;
                $pr->description = $request->get("description")[$count];
                $pr->expected = $request->get("expected")[$count];
                $pr->date_time = $request->get("date_time")[$count];
                $pr->unit_cost = $request->get("unit_cost")[$count];
                $pr->estimated_cost = $request->get("estimated_cost")[$count];
                $pr->status = 1;
                $pr->save();
            }
            $count++;
        }

        Session::put('updated',true);
        return redirect("/prr_meal_page");
    }

    public function prr_meal_pdf()
    {
        $prr_meal_logs = prr_meal_logs::where('route_no',Session::get('route_no'))
            ->where('status',1)
            ->first();
        $meal = prr_meal::where('route_no',Session::get('route_no'))
            ->where('status',1)
            ->where('prr_logs_key',$prr_meal_logs->prr_logs_key)
            ->get();

        $tracking = Tracking::where('route_no',Session::get('route_no'))->first();
        $user = Users::where('id',$tracking->prepared_by)->first();
        $section = Section::where('id',$user->section)->first();
        $division = Division::where('id',$user->division)->first();

        $display = view("prr_meal.prr_meal_pdf",['meal' => $meal,'tracking' => $tracking,'user' => $user,'section' => $section,'division' => $division,'prr_meal_logs' => $prr_meal_logs]);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($display)->setPaper('a4','portrait');

        return $pdf->stream();
    }

    public function prr_meal_category()
    {
        return view('prr_meal.prr_meal_category');
    }

}
