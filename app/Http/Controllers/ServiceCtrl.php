<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Service;

class ServiceCtrl extends Controller
{
    public function index (Request $request)
    {
        $data = Service::all();
        return view('services.index',[
            'data' => $data
        ]);
    }

    public function upsertService (Request $request)
    {
        // dd($request->all());
        $match = array(
            'id' => $request->id
        );
        $data = $request->all();
        $form = Service::updateOrCreate($match,$data);
        
        if($form->wasRecentlyCreated)
        {
            Session::put('service_add',true);
        }
        else
        {
            Session::put('service_update',true);
        }
        
    }

    public function getServiceTypeInfo (Request $request)
    {
        $data = Service::find($request->id);

        return $data;
    }

    public function deleteServiceType (Request $request)
    {
        $data = Service::find($request->id)->delete();

        Session::put('service_delete',true);
    }
}
