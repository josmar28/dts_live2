<?php

namespace App\Http\Controllers;
use App\Tracking;
use App\User;
use App\Section;
use App\Users;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Tracking_Filter;
use App\Http\Controllers\DocumentController as DocumentController;
use App\Tracking_Releasev2;
use Carbon\Carbon;
use App\Tracking_Details;
use Illuminate\Http\Request;

class ReturnCtrl extends Controller
{
    public function index(Request $req)
    {
        // $data = $req->all();
        $section = $req->section;

        $today = Carbon::now()->format('Y-m-d H:i');
        $later = Carbon::now()->subDays(1)->format('Y-m-d H:i');

        $data = Tracking_Details::select('tracking_details.*','tracking_releasev2.released_section_to')
            ->leftJoin('tracking_releasev2', 'tracking_releasev2.route_no', '=', 'tracking_details.route_no')
            ->where('tracking_releasev2.released_section_to',$section)
            ->where('tracking_details.date_in','>=',$later)
            ->where('tracking_details.date_in','<=',$today)
            ->orderBy('tracking_details.date_in','asc') 
            ->get();
            
        // $now = Carbon::now();
        // $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        // $weekEndDate = $now->Weeklater()->format('Y-m-d H:i');


  
  
        $route_no = [];

        foreach ($data as $d)
        {
            $rled = "9";
            $pdoho = "10";
            $datrc = "12";
            $validation = \DB::table('tracking_releasev2')
            ->select('tracking_releasev2.*','section.division as sec_div')
            ->leftJoin('section', 'section.id', '=', 'tracking_releasev2.released_section_to')
            ->where("tracking_releasev2.route_no","=",$d->route_no)
            ->where("tracking_releasev2.released_section_to","=",$section)
            ->where(function ($query) {
                    $query->where('status','=','waiting')
                        ->orWhere('status','=','return');
                })
                ->orderBy('id', 'DESC')
                ->first();
    
            $release = Tracking_Releasev2::where("route_no","=",$d->route_no)
                ->where("released_section_to","=",$section)
                ->where(function ($query) {
                    $query->where('status','=','waiting')
                        ->orWhere('status','=','return');
                })
                ->orderBy('id', 'DESC');
    
                if($release->first()){
                    $user = User::find($release->first()->released_by);
                   $released_by = (Section::find($user->section)) ? Section::find($user->section)->division:'';
                   $minute = DocumentController::checkMinutes($validation->released_date); 
      
                    if($minute <= 45 && ($validation->status == "waiting" || $validation->status == "return" )
                    && $validation->sec_div != $pdoho && $validation->sec_div != $rled && $validation->sec_div != $datrc
                    && $released_by != $pdoho && $released_by != $rled && $released_by != $datrc){
                        $route_no = $d->route_no;
                    }
                    else{
                        $route_no = $d->route_no;
                    } 
            }

        }

        return $data;

    }
}
