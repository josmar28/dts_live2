<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Tracking;
use App\Http\Requests;

class PrController extends Controller
{
    public function index(Request $req)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://192.168.1.55/procurement/login.php',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('username' => 'admin','password' => '1-2-3-4'),
          CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=oappqke20n2p98usrtrp4ee536'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);




        $curl1 = curl_init();

        curl_setopt_array($curl1, array(
        CURLOPT_URL => 'http://192.168.1.55/procurement//pdf/pr/pr.php?id='.$req->barcode,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => array('username' => 'admin','password' => '1-2-3-4'),
        CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=oappqke20n2p98usrtrp4ee536'
        ),
        ));

        header('Content-type: application/pdf');
        $response1 = curl_exec($curl1);

        curl_close($curl1);
        echo $response1;


        // $curl1 = curl_init();

        // curl_setopt_array($curl1, array(
        // CURLOPT_URL => 'http://192.168.106.1.55/procurement/pdf/pr/pr.php?id='.$req->barcode,
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'GET',
        // CURLOPT_POSTFIELDS => array('username' => 'admin','password' => '1-2-3-4'),
        // CURLOPT_HTTPHEADER => array(
        //     'Cookie: PHPSESSID=9hm50upkc9qlqbvlomhdhreotj'
        // ),
        // ));

        // header('Content-type: application/pdf');
        // $response1 = curl_exec($curl1);

        // curl_close($curl1);
        // echo $response1;



    }

    public function checkPrno(Request $req)
    {
        $data = Tracking::where('pr_no',$req->pr_no)->count();
        return $data;
    }

    
}
