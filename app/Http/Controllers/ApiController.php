<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 12/8/2016
 * Time: 8:26 AM
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Tracking;
use App\Average;
use App\IncidentReport;
use App\Tracking_Releasev2;
use App\Tracking_Details;
use App\Tracking_Filter;
use App\chd12_incidenttype;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
        function testTrack(Request $req){
        $route_no = $req->route_no;
        return view("form.testtrack" ,[
            'route_no' => $route_no
        ]);
    }

    public function getUser(Request $req) {
        $user = Users::where('username',$req->username)->first();
        if($user) {
            if(Hash::check($req->password,$user->password)) {
                return json_encode($user);
            }
        }
    }
}   
