<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tracking;
use App\Http\Requests\ValidateSalaryForm;
use App\Tracking_Details;
use App\Http\Requests;

class BillsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }
    
    public function index()
    {
        return view('form.bills');
    }
    
    public function store(ValidateSalaryForm $request){
        $q = new Tracking();
        $route_no = date('Y-').$request->input('prepared_by').date('mdHis');
        $q->route_no = $route_no;
        $q->doc_type = $request->input('doc_type');
        $q->prepared_date = $request->input('prepared_date');
        $q->prepared_by = $request->input('prepared_by');
        $q->amount = $request->input('amount');
        $q->description = $request->input('description');
        $q->save();

        $q = new Tracking_Details();
        $q->route_no = $route_no;
        $q->date_in = $request->input('prepared_date');
        $q->received_by = $request->input('prepared_by');
        $q->delivered_by = $request->input('prepared_by');
        $q->action = $request->input('description');
        $q->save();
        return redirect('document');
    }
}
