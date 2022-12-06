<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;

class OnlineController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    public function online()
    {
        $startTime = date('Y-m-d ').'00:00:00';
        $endTime = date('Y-m-d ').'23:59:59';
        $count = User::where('users.updated_at','>=',$startTime)
            ->leftJoin('section', 'users.section', '=', 'section.id')
            ->where('users.updated_at','<=',$endTime)
            ->where('status',1)
            ->orderBy('lname','asc')
            ->get();
        return $count;
    }
}
