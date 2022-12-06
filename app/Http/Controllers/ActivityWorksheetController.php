<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/17/2016
 * Time: 1:34 PM
 */

namespace App\Http\Controllers;

use App\Tracking;
use App\Tracking_Details;
use Illuminate\Http\Request;
class ActivityWorksheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        $user = $request->user()->fname ." ".$request->user()->mname." ".$request->user()->lname;
        return view('form.act_worksheet')->with('user', $user);
    }
    public function create(Request $request) {
        $tracking = new Tracking();
        $tracking->route_no = date('Y')."-".$request->user()->id.date('mdHis');
        $tracking->prepared_by = $request->user()->id;
        $tracking->prepared_date =  date('Y-m-d H:i:s');
        $tracking->doc_type = $request->input('doctype');
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
}