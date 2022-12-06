<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 12/6/2016
 * Time: 9:31 AM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Tracking;
use App\Tracking_Details;
class GeneralDocument extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request){
        //GET REQUEST
        if($request->isMethod('get')){
            $user = $request->user();
            return view('form.general')
                    ->with('user', $user->firstname. " " .$user->mname." ". $user->lname);
        }
        //POST REQUEST
        if($request->isMethod('post')){

            $tracking = new Tracking();
            $route_no = date('Y')."-".$request->user()->id.date('mdHis');
            $tracking->route_no = $route_no;
            $tracking->prepared_date = date('Y-m-d H:i:s');
            $tracking->prepared_by = $request->user()->id;
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