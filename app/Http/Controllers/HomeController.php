<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Tracking;
use Illuminate\Support\Facades\Session;
use App\Tracking_Details;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }

    }

    public function index(Request $request)
    {
        $request->session()->forget('keyword');
        return view('home');
    }


    function chart(){
        $data = array(
            'data1' => self::_createdDocument(),
            'data2' => self::_acceptedDocument()
        );
        echo json_encode($data);
    }

    function _createdDocument(){
        $id = Session::get('auth')->id;
        for($i=1; $i<=12; $i++){
            $new = str_pad($i, 2, '0', STR_PAD_LEFT);
            $current = '01.'.$new.'.'.date('Y');
            $data['months'][] = date('M/y',strtotime($current));
            $startdate = date('Y-m-d',strtotime($current));
            $end = '01.'.($new+1).'.'.date('Y');
            if($new==12){
                $end = '12/31/'.date('Y');
            }
            $enddate = date('Y-m-d',strtotime($end));
            $count = Tracking::where('prepared_by',$id)
                        ->where('prepared_date','>=',$startdate)
                        ->where('prepared_date','<=',$enddate)
                        ->count();
            $data['count'][] = $count;
        }
        return $data;
    }

    function _acceptedDocument(){
        $id = Session::get('auth')->id;
        for($i=1; $i<=12; $i++){
            $new = str_pad($i, 2, '0', STR_PAD_LEFT);
            $current = '01.'.$new.'.'.date('Y');
            $data['months'][] = date('M/y',strtotime($current));
            $startdate = date('Y-m-d',strtotime($current));
            $end = '01.'.($new+1).'.'.date('Y');
            if($new==12){
                $end = '12/31/'.date('Y');
            }
            $enddate = date('Y-m-d',strtotime($end));
            $count = Tracking_Details::where('received_by',$id)
                ->where('date_in','>=',$startdate)
                ->where('date_in','<=',$enddate)
                ->count();
            $data['count'][] = $count;
        }
        return $data;
    }
}
