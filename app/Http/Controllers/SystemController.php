<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemLogs;
use App\Http\Requests;
use App\Users;
use App\Tracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class SystemController extends Controller
{
    function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }

    static function logDocument($user_id,$id)
    {
        $description = Tracking::find($id)->route_no;
        $user = Users::find($user_id);
        $activity = "Updated";
        $q = new SystemLogs();
        $q->user_id = $user_id;
        $q->name = $user->fname.' '.$user->lname;
        $q->activity = $activity;
        $q->description = $description;
        $q->save();

        return true;
    }
    
    static function logDefault($act,$desc="")
    {
        $user = Users::find(Session::get('auth')->id);
        $q = new SystemLogs();
        $q->user_id = $user->id;
        $q->name = $user->fname.' '.$user->lname;
        $q->activity = $act;
        $q->description = $desc;
        $q->save();

        return true;
    }

    public function migrate()
    {
        return;
        Schema::table('tracking_details', function (Blueprint $table) {
            $table->string('code')->after('route_no');
            $table->integer('alert')->after('code');
        });
        Schema::dropIfExists('tracking_report');
        Schema::create('tracking_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->dateTime('date_reported');
            $table->integer('reported_by');
            $table->string('section_reported');
            $table->rememberToken();
            $table->timestamps();
        });
    }

}
