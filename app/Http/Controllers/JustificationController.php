<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/16/2016
 * Time: 10:09 AM
 */

namespace App\Http\Controllers;

use App\Tracking;
use App\Tracking_Details;
use App\User;
use Symfony\Component\HttpFoundation\Request;

class JustificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        if($request->isMethod('get')) {
            $name = $request->user()->fname." ".$request->user()->mname." ".$request->user()->lname;
           // return view('form.justification_letter')->with('user', $user);
            return view('form.just_letter_sample')->with('name', $name);
        }

        if($request->isMethod('post')){

            $tracking = new Tracking();
            $tracking->route_no = date('Y')."-".$request->user()->id.date('mdHis');
            $tracking->prepared_by = $request->user()->id;
            $tracking->prepared_date = date('Y-m-d H:i:s');
            $tracking->description = $request->input('description');
            $tracking->route_from = $request->input('routed_from');
            $tracking->route_to = $request->input('routed_to');
            $tracking->doc_type = $request->input('doctype');

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