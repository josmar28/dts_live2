<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/16/2016
 * Time: 10:55 AM
 */

namespace App\Http\Controllers;

use App\Tracking;
use Symfony\Component\HttpFoundation\Request;
use App\Tracking_Details;

class OfficeOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create(Request $request){
        if($request->isMethod('get')) {
            $user = $request->user()->fname . " " . $request->user()->mname . " " . $request->user()->lname;
            return view('form.office_order_simple')->with('user', $user);
        }
        if($request->isMethod('post')) {
            $tracking = new Tracking();
            $tracking->route_no = date('Y')."-".$request->user()->id.date('mdHis');
            $tracking->prepared_by = $request->user()->id;
            $tracking->prepared_date = date('Y-m-d H:i:s');
            $tracking->route_from = $request->input('routed_from');
            $tracking->route_to = $request->input('routed_to');
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
}