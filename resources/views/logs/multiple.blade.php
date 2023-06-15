<?php
use Illuminate\Support\Facades\Session;
use App\Users;
use App\Section;
use App\Release;
use App\Designation;
use App\Division;
use App\Tracking_Details;
use App\Tracking_Releasev2;
use App\Http\Controllers\DocumentController as Doc;
use Illuminate\Support\Facades\Input;

$date = date("F j, Y");
$designation = Session::get('auth')->designation;
$division = Session::get('auth')->division;
$section = Session::get('auth')->section;

$sec = (Section::find($section)) ? Section::find($section)->description:'';
$des = (Designation::find($designation)) ? Designation::find($designation)->description:'';
$div = (Division::find($division)) ? Division::find($division)->description:'';

?>
<html>
<title>DTS Transmittal</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<head>
    <link href="{{ asset('resources/assets/css/print.css') }}" rel="stylesheet">
    <style>
        html {
            font-size:0.8em;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .letter-head {
    width: 100%;
}
.letter-head td {
    border:0px solid #000;
}
.letter-head td {
    padding:10px;
}
    </style>
</head>
<body>
                
<table class="letter-head" cellpadding="0" cellspacing="0">
    <tr>
        <td width="20%"><center><img src="{{ asset('public/img/doh.png') }}" width="80"></center></td>
        <td width="60%">
            <center style="font-size:11;">
            <h4 style="margin:0;">Republic of the Philippines</h4>
            <h4 style="margin:0;">Department of Health</h4>
            <h3 style="margin:0;">Center for Health Development</h3>
            <h4 style="margin:0;">SOCCSKSARGEN Region</h4>
            </center>
        </td>
        <td width="20%"><center><img src="{{ asset('public/img/icon22.png') }}" width="170px"></center></td>
    </tr>

</table>
<h4 style="margin:0;">Date: {{ $date }}</h4>

<br>
<table class="table table-bordered table-hover table-striped " style="font-size:11;">
    <thead> 
    <tr>
        <th width="12%" style="text-align:center;"> <p>DEPARTMENT OF HEALTH<br>
                            REGIONAL OFFICE NO. XII<br>
                           <u> ARISTIDES CONCEPCION TAN, MD, MPH, CESO III</u>
                               <br> REGIONAL DIRECTOR IV
                           </p></th>
        <th width="29%" style="text-align:center";>Title of Documents</th>
        <th width="12%" style="text-align:center;">Received by <br> (Date & Time)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($result as $doc)
    <tr>
    <?php
            $out = Doc::deliveredDocument($doc->route_no,$doc->delivered_by,$doc->doc_type);
            // print_r($out->received_by);
            ?>
             @if($out)
                <td>
                  
                    <?php 
                        if($user = Users::find($out->received_by)){
                            $rec_fname = $user->fname;
                            $rec_lname = $user->lname;
                            $rec_section = Section::find($user->section) ? Section::find($user->section)->description:'';
                        }else{
                            $id = Session::get('auth')->id;
                            $user = Tracking_Releasev2::where('released_by',$doc->delivered_by)
                                ->where('route_no',$doc->route_no)
                                ->orderBy('id','desc')
                                ->first();

                            $rec_fname = " ";
                            $rec_lname = " ";
                            $rec_section = Section::find($user->released_section_to) ? Section::find($user->released_section_to)->description:'';
                        }
                    ?>
                    {{ $rec_fname }}
                    {{ $rec_lname }}
                    <br>
                    <em>({{ $rec_section }})</em>
                </td>
                @else
                
                <?php 
                    $sec = Doc::secDocument($doc->route_no,$doc->doc_type);
                 
                    if($user = Users::find($sec->delivered_by)){
                        $rec_fname = $user->fname;
                        $rec_lname = $user->lname;
                        $rec_section = Section::find($user->section)->description;
                    }else{
                        $id = Session::get('auth')->id;
                        $user = Tracking_Releasev2::where('route_no',$doc->route_no)
                            ->orderBy('id','desc')
                            ->first();

                        $rec_fname = " ";
                        $rec_lname = " ";
                        $rec_section = Section::find($user->released_section_to)->description;
                    }
                 ?>
                <td>
                {{ $rec_fname }}
                    {{ $rec_lname }}
                    <br>
                    <em>({{ $rec_section }})</em>
                </td>

                      
            @endif
            <td>
                <b>{{ $doc->route_no }}</b>
                <br>
                {!! nl2br($doc->description) !!}
            </td>
     
            <td>
               
            </td>
        </tr>
    @endforeach
    </tbody>
    
</table>
<div style="text-align:right; font-size:11;" >
<h4> DOH-ROXII-MSDRS-SOP-01 Form3 Rev0</h4>
    </DIV>

                    <h4>Prepared By: </h4>
<p style="margin-left:100px;"><u>{{ Session::get('auth')->fname }} {{ Session::get('auth')->lname }}</u>
<br> {{ $des }}</p>
        
<br><h4>Received By: </h4>
<p style="margin-left:100px;">Printed Name & Signature:_____________________________<br>
<br>Date: __________________________</p>
<br>
<p>TRN: {{ $TRN }}</p>

        <!-- <div class="col-md-6" >
        <div style="text-align: left;">
        <h5 style="margin-left:10px;">Printed By:</h5>
    
                <span style="margin-left:30px">     ({{ Section::find(Session::get('auth')->section)->description }}) </span><br> 
                <span style="margin-left:30px">  {{ Session::get('auth')->fname }} {{ Session::get('auth')->lname }} </span><br>
         </div>
                    </div>
        <div class="col-sm-6">
        <div style="text-align: right;">
        <h5 style="margin-right:px;">Received By:</h5>
    
                <span style="margin-right:30px">Printed Name & Signature: _______________________ </span><br> 
                <span style="margin-right:30px"> Date:_______________________ </span><br>

        </div>
        </div> -->
</div>
</body>
</html>
<script type="text/javascript">
     function searchDocument(){
            $('.loading').show();
            setTimeout(function(){
                return true;
            },2000);
        }
</script>