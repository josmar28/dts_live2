<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 1/10/2017
 * Time: 10:30 AM
 */

namespace App\Http\Controllers;


use App\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Feedback1Controller extends Controller
{
    public function __construct()
    {

    }

    public function sendFeedback(Request $request){
        $feedback = new Feedback();
        $feedback->userid = "anonymous";
        $feedback->message = $request->input('feedback');
        $feedback->stat_id = "-1";
        $feedback->is_read = "0";
        $feedback->save();
        return Redirect::back()->with('successFeedback', 'Successfully send feedback!');
    }


}