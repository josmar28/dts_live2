<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/18/2016
 * Time: 10:27 AM
 */

namespace App\Http\Controllers;


use App\Designation;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        $d = Designation::paginate(10);
        return view('designation.list')->with('designations',$d);
    }
    public function create(Request $request){
        if($request->isMethod('get')){
            return view('designation.create');
        }
        if($request->isMethod('post')){
            $d = new Designation();
            $d->description = $request->input('designation');
            $d->save();
            Session::put('designation_create', true);
            return redirect('designation');
        }
    }

    public function edit(Request $request){
        if($request->isMethod('get')) {
            $d = Designation::find($request->id);
                return view('designation.edit_designation',[
                    'd' => $d
                ]);
        }
        if($request->isMethod('post')){
            $match = array(
                'id' => $request->id
            );
            $data = array(
                'description' => $request->designation
            );
            $form = Designation::updateOrCreate($match,$data);
            Session::put('designation_update', true);
            return redirect('designation');
            
        }
    }
    public function search(Request $request){
        $designation = Designation::where('description','LIKE', "%". $request->input('search') ."%")->paginate(10);
        // if(isset($designation) and count($designation) > 0) {
        //     return view('designation.list')->with('designations', $designation);
        // }
        return view('designation.list',[
            'designations' => $designation
        ]);
    }
    public function remove(Request $request) {
        $d = Designation::find($request->input('id'));
        $d->delete();
        Session::put('designation', true);
        
        return 'true';
    }
}