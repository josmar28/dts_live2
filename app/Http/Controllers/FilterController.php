<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tracking_Filter;

use App\Http\Requests;

class FilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user_priv');
    }

    public function index(){
        $documents = Tracking_Filter::all();
        return view('document.filter',['documents' => $documents ]);
    }

    public function update(Request $request){
        $column = $request->column;
        $doc_type = $request->doc_type;
        $value = $request->value;

        Tracking_Filter::where('doc_type',$doc_type)
            ->update([$column=>$value]);
        return $column;
    }
}
