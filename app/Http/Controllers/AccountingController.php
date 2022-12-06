<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Tracking_Details;
use App\Tracking;

class AccountingController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('accounting');
    }

    function accept(){
        return view('document.acceptaccounting');
    }

    public function save(Request $request){
        $user = Session::get('auth');
        $id = $user->id;
        $route_no = $request->route_no;

        $doc = Tracking::where('route_no',$route_no)
            ->orderBy('id','desc')
            ->first();
        $dv_no = Tracking::where('dv_no',$request->dv_no)->first();
        if($doc){
            if($dv_no && $request->dv_no!=null){
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

                if($request->dv_no!=null)
                {
                    Tracking::where('route_no',$route_no)
                        ->update(['dv_no'=>$request->dv_no]);
                }
                return json_encode(array('message' => 'SUCCESS'));
            }
        }else{
            return json_encode(array('message' => 'ERROR'));
        }
    }
}
