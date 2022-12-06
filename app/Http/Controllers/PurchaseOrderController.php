<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Tracking;
use Illuminate\Support\Facades\Session;
use Milon\Barcode\DNS1D;
use Dompdf\Dompdf;
use App\Tracking_Details;
use App;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PurchaseOrder(){
        return view('form.PurchaseOrder');
    }

    public function PurchaseOrderSave(Request $request){
        $route_no = date('Y-') . $request->user()->id . date('mdHis');
        $description = 'PO '.$request->get('po_no').' dtd '.$request->get('po_date').'
                        PR '.$request->get('pr_no').' dtd '.$request->get('pr_date').'
                        <b>'.$request->get('additional_info').'</b>';

        $tracking = new Tracking();
        $tracking->route_no = $route_no;
        $tracking->doc_type = $request->get('doc_type');
        $tracking->prepared_date = $request->get('prepared_date');
        $tracking->prepared_by = $request->get('prepared_by');
        $tracking->description = $description;
        $tracking->pr_no = $request->get('pr_no');
        $tracking->po_no = $request->get('po_no');
        $tracking->supplier = $request->get('supplier');

        $tracking->save();

        $q = new Tracking_Details();
        $q->route_no = $route_no;
        $q->date_in = $request->get('prepared_date');
        $q->received_by = $request->get('prepared_by');
        $q->delivered_by = $request->get('prepared_by');
        $q->action = $description;
        $q->save();

        return redirect("/document");
    }

    /*public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }*/
}
