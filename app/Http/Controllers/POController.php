<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class POController extends Controller
{
    public function index(Request $req)
    {


    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://192.168.1.197:88//Default4.aspx?PO='.$req->po_num,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Cookie: ASP.NET_SessionId=c10rtx5f4wvw2fo15dsayy50'
      ),
    ));


    header('Content-type: application/pdf');
    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;



        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'http://192.168.1.197:8080//Default4.aspx?PO='.$req->po_num,
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'GET',
        //   CURLOPT_HTTPHEADER => array(
        //     'Cookie: ASP.NET_SessionId=amdk4ik355q5i3fkqivuywwq'
        //   ),
        // ));


        // header('Content-type: application/pdf');
        // $response = curl_exec($curl);
        
        // curl_close($curl);
        // echo $response;
        

    }
}
