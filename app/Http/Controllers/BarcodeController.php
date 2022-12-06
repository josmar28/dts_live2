<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/14/2016
 * Time: 12:47 PM
 */

namespace App\Http\Controllers;
use Dompdf\Dompdf;
use Milon\Barcode\DNS1D;
use Illuminate\Routing\Controller;
use App;

class BarcodeController extends Controller
{
    public function barcode() {
        $d = new DNS1D();

        $print = $d->getBarcodeHTML("9780691147727", "EAN13");
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($print);
        return $pdf->stream();
    }
}