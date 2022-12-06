<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Calendar;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function calendar(Request $request)
    {
        $calendar = new Calendar();
        $calendar->event_id = $request->get('event_id');
        $calendar->title = $request->get('title');
        $calendar->start = $request->get('start');
        $calendar->end = $request->get('end');
        $calendar->backgroundColor = $request->get('background_color');
        $calendar->borderColor = $request->get('border_color');
        $calendar->save();

        return redirect('/calendar');
    }

    public function calendar_update(Request $request)
    {
        Calendar::where('event_id',$request->get('event_id'))
            ->update(['end' => $request->get('end')]);
    }
}
