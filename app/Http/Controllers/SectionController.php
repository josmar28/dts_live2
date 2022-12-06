<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Section;
use App\Division;
use App\Users;
use App;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user_priv');
    }

    public function section()
    {
        $keyword = Session::get('searchSection');
        $section = Section::where('description','like',"%$keyword%")
                    ->orderBy('description', 'asc')->paginate(10);
        return view('section.section', ['section' => $section]);
    }

    public function searchSection(Request $request)
    {
        Session::put("search",$request->search);
        return $this->searchSectionSave();
    }

    public function searchSectionSave()
    {
        $section = Section::where('description','like',"%".Session::get('search')."%")->orderBy('description','asc')->paginate(10);
        return view('section.section',['section' => $section ]);
    }

    public function addSection()
    {
        $division = Division::all();
        $user = Users::all()->sortBy("fname");
        return view('section.addSection', ['user' => $user, 'division' => $division]);
    }

    public function addSectionSave(Request $request){
        $section = new Section();
        $section->code = $request->get('code');
        $section->division = $request->get('division');
        $section->description = $request->get('description');
        $section->head = $request->get('head');
        $section->save();
        Session::put('section_save',true);
        return redirect("section");
    }
    
    public function deleteSection($id){
        $section = Section::find($id);
        $section->delete();
        Session::put('section_delete',true);
    }

    public function updateSection($id,$divisionId,$headId){
        ///SECTION INFO
        $section = Section::where('id','=',$id)->first();
        $code = $section['code'];
        $description = $section['description'];
        //DIVISION INFO
        $divisionAll = Division::all();
        $division = Division::where('id','=',$divisionId)->first();
        $divisionId = $division['id'];
        $divisionName = $division['description'];
        //HEAD INFORMATION
        $head = Users::where('id', '=', $headId)->first();
        $headId = $head['id'];
        $headName  = $head['fname'].' '.$head['mname'].' '.$head['lname'];
        $user = Users::all()->sortBy("fname");
        return view('section.updateSection',['id' => $id ,'user' => $user,'divisionAll' => $divisionAll,'headId' => $headId,'headName' => $headName,'code' => $code,'description' => $description,'divisionId' => $divisionId, 'divisionName' => $divisionName] );
    }

    public function updateSectionSave(Request $request){
        $section = Section::find($request->get('id'));
        $section->code=$request->get('code');
        $section->division=$request->get('division');
        $section->description=$request->get('description');
        $section->head=$request->get('head');
        $section->save();
        Session::put('section_update',true);
        return redirect('section');
    }

    public static function getHead($id){
        $user = Users::find($id);
        return $user['fname'].' '.$user['mname'].' '.$user['lname'];
    }
    public function checkSection(Request $request) {
        $section = Section::where('description',$request->input('description'))->first();
        if(isset($section) and count($section) > 0) {
            return json_encode(array('status' => 'ok'));
        }
        return json_encode(array('status' => 'false'));
    }
    public function checkSectionUpdate(Request $request) {
        $section = Section::where('description',$request->input('description'))->first();
        if(isset($section) and count($section) > 0) {
            return json_encode(array('status' => 'ok'));
        }
        return json_encode(array('status' => 'false'));
    }

    static function getSections($id){
        $sections = Section::where('division',$id)->orderBy('description','asc')->get();
        return $sections;
    }

}