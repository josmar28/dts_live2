<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/15/2016
 * Time: 4:25 PM
 */

namespace App\Http\Controllers;
use App\Tracking_Details;
use Symfony\Component\HttpFoundation\Request;
use App\User;
use App\Tracking;
class AppLeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request) {
        $tracking = new Tracking();
        $tracking->route_no = date('Y')."-".$request->user()->id.date('mdHis');
        $tracking->cdo_applicant = $request->input('applicant_name');
        $tracking->cdo_day = $request->input('days_leave');
        $tracking->event_daterange = $request->input('daterange');
        $tracking->doc_type = $request->input('doctype');
        $tracking->prepared_date = date('Y-m-d H:i:s');
        $tracking->prepared_by = $request->user()->id;
        $tracking->description = $request->input('description');
        $tracking->save();

        $a = new Tracking_Details();
        $a->route_no = $tracking->route_no;
        $a->date_in = $tracking->prepared_date;
        $a->received_by = $request->user()->id;
        $a->delivered_by = $request->user()->id;
        $a->action = $request->input('description');
        $a->save();
        return redirect('document');

    }

    public function index(Request $request) {
        $user = $request->user()->fname." ".$request->user()->mname." ".$request->user()->lname;
        return view('form.application_cdo')->with('user',$user);
    }
}