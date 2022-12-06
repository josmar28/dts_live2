<?php
$total = 0;
$item_no = 1;
$prr_logs_count = 0;
use App\Users;
use App\Designation;
use App\prr_meal;
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
if(count($prr_meal_logs) >= 1){
    foreach($prr_meal_logs as $prr_meal_logs):
    $prr_logs_count++;
    $prr_meal = prr_meal::where("route_no",$prr_meal_logs->route_no)
            ->where("prr_logs_key",$prr_meal_logs->prr_logs_key)
            ->get();
?>
    <body>
    <div style="padding: 5%;margin-top: -5%">
        <span style="color: blue">Updated Date:</span> <span style="color:green">{{ date('M d, Y h:i:s A',strtotime($prr_meal_logs->updated_date)) }}</span>
        <table class="letter-head" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="7" class="align">
                    <strong>PURCHASE REQUEST LAST CREATED</strong>
                </td>
            </tr>
            <tr>
                <td id="border-left"><b>Item No</b></td>
                <td id="border-right"><b>Qty</b></td>
                <td id="border-right"><b>Unit of Issue</b></td>
                <td width="35%" id="border-right"><b>Item Description</b></td>
                <td id="border-right"><b>Stock No.</b></td>
                <td><b>Estimated Unit Cost</b></td>
                <td id="border-right"><b>Estimated Cost</b></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="global_title align">
                    <i>{{ $prr_meal_logs->global_title }}</i>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tbody>
            @foreach($prr_meal as $row)
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
                                echo nl2br($row->description);
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