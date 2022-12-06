<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getIndex(){
        return 'here';   
    }
    
    public function getSalary(){
        return view('document.salary');
    }
}
