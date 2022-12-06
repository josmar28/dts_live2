<?php
$total = 0;
$item_no = 1;
$prr_logs_count = 0;
use App\Users;
use App\Designation;
use App\prr_supply;
?>
<html>
<head>
    <link href="{{ asset('resources/assets/css/print.css') }}" rel="stylesheet">
    <style>
        html {
            font-size:x-small;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        body {
            margin-bottom: 50px;
        }
        #border{
            border-collapse: collapse;
            border: none;
        }
        #border-top{
            border-collapse: collapse;
            border-top: none;
        }
        #border-right{
            border-collapse: collapse;
            border:1px solid #000;
        }
        #border-bottom{
            border-collapse: collapse;
            border-bottom: none;
        }
        #border-bottom-t{
            border-collapse: collapse;
            border-top:1px solid red;
            border-bottom:1px solid red;
        }
        #border-left{
            border-collapse: collapse;
            border:1px solid #000;
        }
        .align{
            text-align: center;
        }
        .align-top{
            vertical-align : top;
        }
        .table1 {
            width: 100%;
        }
        .table1 td {
            border:1px solid #000;
        }
    </style>
</head>
<br>
<?php
if(count($prr_logs) >= 1){
    foreach($prr_logs as $prr_logs):
        $prr_logs_count++;
        $prr_supply = prr_supply::where("route_no",$prr_logs->route_no)
                                      ->where("prr_logs_key",$prr_logs->prr_logs_key)
                                      ->get();
?>
    <body>
        <div style="padding: 5%;margin-top: -5%">
            <span style="color: blue">Updated Date:</span> <span style="color:green">{{ date('M d, Y h:i:s A',strtotime($prr_logs->updated_date)) }}</span>
            {{--<table class="letter-head" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="border" class="align"><img src="{{ asset('resources/img/doh.png') }}" width="100"></td>
                    <td width="90%" id="border">
                        <div class="align small-text" style="margin-top:-10px;">
                            Republic of the Philippines<br>
                            <strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO. VII</strong><br>
                            Osmeña Boulevard, Cebu City, 6000 Philippines<br>
                            Regional Director’s Office Tel. No. (032) 253-6355 Fax No. (032) 254-0109<br>
                            Official Website: http://www.ro7.doh.gov.ph Email Address: dohro7@gmail.com<br>
                        </div>
                    </td>
                    <td id="border" class="align"><img src="{{ asset('resources/img/ro7.png') }}" width="100"></td>
                </tr>
            </table>--}}
            <table class="letter-head" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="7" class="align">
                        <strong>PURCHASE REQUEST LAST CREATED</strong>
                    </td>
                </tr>
                {{--<tr>
                    <td colspan="2">Department:</td>
                    <td rowspan="3" colspan="2">{{ $division->description }}<br> {{ $section->description }}</td>
                    <td colspan="2">PR No:</td>
                    <td><span>Date: {{ substr($tracking->prepared_date,5,2).'/'.substr($tracking->prepared_date,8,2).'/'.substr($tracking->prepared_date,2,2) }}</span></td>
                </tr>
                <tr>
                    <td colspan="2">Section:</td>
                    <td colspan="2">SAI No.:</td>
                    <td>Date: </td>
                </tr>
                <tr>
                    <td colspan="2">Unit:</td>
                    <td colspan="2">ALOBS No.:</td>
                    <td>Date: </td>
                </tr>--}}
                <tr>
                    <td id="border-left"><b>Item No</b></td>
                    <td id="border-right"><b>Qty</b></td>
                    <td id="border-right"><b>Unit of Issue</b></td>
                    <td width="35%" id="border-right"><b>Item Description</b></td>
                    <td id="border-right"><b>Stock No.</b></td>
                    <td><b>Estimated Unit Cost</b></td>
                    <td id="border-right"><b>Estimated Cost</b></td>
                </tr>
                <tbody>
                @foreach($prr_supply as $row)
                    <tr>
                        <td id="border-bottom" class="align-top">{{ $item_no }}</td>
                        <td id="border-bottom" class="align-top">{{ $row->qty }}</td>
                        <td id="border-bottom" class="align-top">{{ $row->issue }}</td>
                        <td id="border-bottom" class="align-top">
                                    <span class="small-text">
                                        <?php
                                        $total += $row->estimated_cost;
                                        $count = 0;
                                        $item_no++;
                                        echo "<strong>".$row->description."</strong>"."<br>";
                                        if(strlen($row->specification) <= 35){
                                            echo "<br>".$row->specification."<br>";
                                        } else {
                                            for($i=0;$i<=strlen($row->specification);$i++){
                                                if($i % 35 == 0){
                                                    echo "<br>".substr($row->specification,$count,35)."<br>";
                                                    $count = $count + 35;
                                                }
                                            }
                                        }
                                        ?>
                                    </span>
                        </td>
                        <td id="border-bottom"></td>
                        <td id="border-bottom" class="align-top"><span style="font-family: DejaVu Sans;">&#x20b1; {{ number_format($row->unit_cost,2) }}</span></td>
                        <td id="border-bottom" class="align-top"><strong style="color: mediumvioletred;"><span style="font-family: DejaVu Sans;">&#x20b1; </span> {{ number_format($row->estimated_cost,2) }}</strong></td>
                    </tr>
                @endforeach
                </tbody>
                <tr>
                    <td id="border-top"></td>
                    <td id="border-top"></td>
                    <td id="border-top"></td>
                    <td id="border-top" width="35%"><br><br> Prepared By:<br><br><u>{{ $user->fname.' '.$user->mname.' '.$user->lname }}</u><br>Programmer</th>
                    <td id="border-top"></td>
                    <td id="border-top"></td>
                    <td id="border-top"></td>
                </tr>
                <tr>
                    <td class="align" colspan="6"><b>TOTAL</b></td>
                    <td class="align-top"><strong style="color: red;"><span style="font-family: DejaVu Sans;">&#x20b1; </span> {{ number_format($total,2) }}</strong></td>
                </tr>
            </table>
            <!--
            <table class="letter-head" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="7" class="align"><b style="margin-right:5%">CERTIFICATION</b></td>
                </tr>
                <tr>
                    <td id="border-bottom" colspan="7">This is to certify that diligent efforts have been exerted to ensure that the price/s indicated above (in relation to the specification) is/are within the prevailing market price/s.
                        <br><br>
                        Requested By:
                    </td>
                </tr>
                <tr>
                    <td id="border-top" colspan="7" class="align"><u><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ Users::find($tracking->requested_by)->fname.' '.Users::find($tracking->requested_by)->mname.' '.Users::find($tracking->requested_by)->lname }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><br>{{ \App\Designation::find(Users::find($tracking->requested_by)->designation)->description }}</u></td>
                </tr>
                <tr>
                    <td colspan="7" id="border-bottom">Purpose: <b>{{ $tracking->purpose }}</b></td>
                </tr>
                <tr>
                    <td colspan="7" id="border-top">Chargeable to: <b>{{ $tracking->source_fund }}</b></td>
                </tr>
            </table>
            <table class="table1" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="border-bottom" width="15%"></td>
                    <td id="border-bottom" width="40%">&nbsp;Recommending Approval:</td>
                    <td id="border-bottom" width="40%">&nbsp;Approved By:</td>
                </tr>
                <tr>
                    <td id="border-top border-bottom">&nbsp;Signature:</td>
                    <td id="border-top border-bottom"></td>
                    <td id="border-top border-bottom"></td>
                </tr>
                <tr>
                    <td id="border-top border-bottom">&nbsp;Printed Name:</td>
                    <td id="border-top border-bottom" class="align"><u><b>{{ Users::find($tracking->division_head)->fname.' '.Users::find($tracking->division_head)->mname.' '.Users::find($tracking->division_head)->lname }}</b></u></td>
                    <td id="border-top border-bottom" class="align"><u><b>Jaime S. Bernadas, MD, MGM, CESO III</b></u></td>
                </tr>
                <tr>
                    <td id="border-top" >&nbsp;Designation:</td>
                    <td id="border-top" class="align">&nbsp;{{ \App\Designation::find(Users::find($tracking->division_head)->designation)->division_head }}</td>
                    <td id="border-top" class="align">&nbsp;Director IV</td>
                </tr>
            </table>
            -->
            <div style="position:absolute; left: 30%;margin-top:1%" class="align">
                <?php echo DNS1D::getBarcodeHTML(Session::get('route_no'),"C39E",1,28) ?>
                <font class="route_no">{{ Session::get('route_no') }}</font>
            </div>
        </div>
    </body>
    <hr>
<?php
        endforeach;
    } else {
        echo
        '<div>
            <h4 class="alert alert-success text-center"><strong>No update history</strong></h4>
        </div>';
    }
?>
</html>