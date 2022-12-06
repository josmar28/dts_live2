<?php

namespace App\Http\Controllers;


use App\Tracking;
use Illuminate\Http\Request;
use App\Tracking_Details;

class TEVController extends Controller
{
    public function index() {
        return view('form.tev');
//        return view('form.sample');
    }
    public function create() {
        $tracking = new Tracking();
    }
    public function store(Request $request){
        $q = new Tracking();
        $route_no = date('Y-').$request->input('prepared_by').date('mdHis');
        $q->route_no = $route_no;
        $q->doc_type = $request->input('doc_type');
        $q->prepared_date = $request->input('prepared_date');
        $q->prepared_by = $request->input('prepared_by');
        $q->amount = $request->input('amount');
        $q->description = $request->input('description');
        $q->event_daterange = $request->input('daterange');
        $q->save();

        $r = new Tracking_Details();
        $r->route_no = $route_no;
        $r->date_in = $request->input('prepared_date');
        $r->received_by = $request->input('prepared_by');
        $r->delivered_by = $request->input('prepared_by');
        $r->action = $request->input('description');
        $r->save();
        return redirect('document');

    }
//    public function store(Request $request){
//        echo '<pre>';
//        print_r($_POST);
//    }
}