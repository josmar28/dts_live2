<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Tracking_Details;

class AppendController extends Controller
{
    public function appendOutgoingDocument($id,$route_no){
        $user = Session::get('auth');
        $code1 = 'accept;'.$user->section;
        $code2 = 'return;'.$user->section;
        $data = Tracking_Details::select(
            'date_in',
            'id',
            'route_no',
            'received_by',
            'code',
            'delivered_by',
            'action'
        )
            ->where('route_no','=',$route_no)
            ->where(function($q) use($code1,$code2) {
                $q->where('code', $code1)
                    ->orwhere('code', $code2);
            })
        ->first();

        return view('append.appendOutgoingDocument',[
            "data" => $data
        ]);


    }
}
