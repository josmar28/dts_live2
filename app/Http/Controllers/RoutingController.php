<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/10/2016
 * Time: 11:07 AM
 */

namespace App\Http\Controllers;
use App\Tracking;
use Illuminate\Routing\Controller;
use App;
use Illuminate\Http\Request;
use App\Tracking_Details;

class RoutingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function routing_slip() {
        return view('form.routing_slip');
    }
    public function create(Request $request) {
        $tracking = new Tracking();
        $route_no = date('Y')."-".$request->user()->id.date('mdHis');
        $tracking->route_no = $route_no;
        $tracking->prepared_date = date('Y-m-d H:i:s');
        $tracking->prepared_by = $request->user()->id;
        $tracking->route_from = $request->input('routed_from');
        $tracking->route_to =   $request->input('routed_to');
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