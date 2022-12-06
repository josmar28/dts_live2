<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Tracking;
use App\Tracking_Details;

class BudgetController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('budget');
    }

    function accept(){
        return view('document.acceptbudget');
    }

    public function save(Request $request){
        $user = Session::get('auth');
        $id = $user->id;
        $route_no = $request->route_no;
        $last = 0;

        $doc = Tracking::where('route_no',$route_no)
            ->orderBy('id','desc')
            ->first();
        $ors_no = Tracking::where('ors_no',$request->ors_no)->first();
        if($doc){
            if($ors_no && $request->ors_no!=null){
                return json_encode(array('message' => 'DUPLICATE'));
            }else{
                $document = Tracking_Details::where('route_no',$route_no)
                    ->orderBy('id','desc')
                    ->first();
                if($document):
                    Tracking_Details::where('route_no',$route_no)
                        ->where('received_by',$document->received_by)
                        ->update(['status'=> 1]);
                    $received_by = $document->received_by;
                else:
                    $received_by = $doc->prepared_by;
                endif;
                $q = new Tracking_Details();
                $q->route_no = $route_no;
                $q->date_in = date('Y-m-d H:i:s');
                $q->received_by = $id;
                $q->delivered_by = $received_by;
                $q->save();

                if($request->ors_no!=null){
                    Tracking::where('route_no',$route_no)
                        ->update(['ors_no'=>$request->ors_no,'fund_source_budget'=>$request->fund_source]);
                }
                return json_encode(array('message' => 'SUCCESS'));
            }
        }else{
            return json_encode(array('message' => 'ERROR'));
        }
    }
}
