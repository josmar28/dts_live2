<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/10/2016
 * Time: 1:58 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\User;
use App\Tracking;
use App\Tracking_Details;
class MailLetterIncomingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function incoming_letter(Request $request) {
        if($request->isMethod('get')){
            $user = User::find($request->user()->id);
            $name = '';
            if(isset($user) and count($user) > 0){
                $name = $user->fname ." ". $user->mname ." ". $user->lname;
            }
            return view('form.incoming_letter')->with('name',$name);
        }
        if($request->isMethod('post')){
            $tracking = new Tracking();
            $route_no = date('Y')."-".$request->user()->id.date('mdHis');
            $tracking->route_no = $route_no;
            $tracking->prepared_date = date('Y-m-d H:i:s');
            $tracking->prepared_by = $request->user()->id;
            $tracking->route_from = $request->input('routed_from');
            $tracking->route_to =   $request->input('routed_to');
            $tracking->doc_type = $request->input('doctype');
            $tracking->description = $request->input('remarks');
            $tracking->save();

            $a = new Tracking_Details();
            $a->route_no = $tracking->route_no;
            $a->date_in = $tracking->prepared_date;
            $a->received_by = $request->user()->id;
            $a->delivered_by = $request->user()->id;
            $a->action = $request->input('remarks');
            $a->save();
            return redirect('document');
        }
    }
}