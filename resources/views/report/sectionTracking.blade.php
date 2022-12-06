<?php
use App\Http\Controllers\ReleaseController as Rel;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Section Logs</title>
    <link href="{{ asset('resources/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        table tr th {
            color:#fff;
            font-size: 1.1em;
        }
    </style>
</head>
<body style="background:#ccc;">
<div class="col-sm-12" style="margin-top:20px;">
    <table class="table table-bordered table-hover" style="background: #fff;">
        <tbody>
            <tr style="background:#325171">
                <th width="20%" class="center">Route No/Action</th>
                <th width="10%" class="center">Receive By</th>
                <th width="10%" class="center">Action/Receive Date</th>
                <th width="10%" class="center">Released To</th>
                <th width="10%" class="center">Released Remarks</th>
                {{--<th width="10%" class="center"></th>--}}
            </tr>
        <?php $count=0; ?>
        @foreach($data as $row)
            <?php
                /*if($previous = \App\Tracking_Details::where('route_no','=',$row->route_no)->where('id','<',$row->id)->first()){
                    $previousId = $previous->id;
                    $previousRoute = $previous->route_no;
                } else {
                    $previousId = '';
                    $previousRoute = '';
                }
                if($next = \App\Tracking_Details::where('route_no','=',$row->route_no)->where('id','>',$row->id)->first()){
                    $nextId = $next->id;
                    $nextRoute = $next->route_no;
                    $nextDatein = $next->date_in;
                } else {
                    $nextId = '';
                    $nextRoute = '';
                    $nextDatein = $row->date_in;
                }*/
            ?>
            <?php $count++; ?>
            <tr>
                <td>
                    <b>{{ $row->route_no }}</b>
                    <br><br>
                    <small style="color: #1e8e34;"> {{ $row->received_remarks }}</small>
                </td>
                <td>
                    <b>{{ $row->received_by }}</b><br>
                    (<small style="color: #ff4374">{{ $row->section }}</small>)</td>
                <td>
                    <small style="color: #1e8e34;"> {{ $row->action }}</small><br><br>
                    <small>({{ date('M d, Y',strtotime($row->date_in)) }} {{ date('h:i:s A',strtotime($row->date_in)) }})</small>
                </td>
                <td>
                    <?php
                        if($row->released_to != $row->section && isset($row->released_to)){
                            echo '<b>'.$row->released_to.'</b><br><small style="color:#F06E20;">('.date('M d, Y',strtotime($row->released_date)).' '.date('h:i:s A',strtotime($row->released_date)).')</small>';
                        }
                    ?>
                </td>
                <td style="color:#232117">
                    {!! nl2br($row->remarks) !!}
                </td>
                {{--<td>
                    <span style="color: green">Previous: {{ $previousId }}|{{ $previousRoute }}</span><br>
                    <span style="color: red">Next: {{ $nextId }}|{{ $nextRoute }}</span>
                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('resources/assets/js/jquery.min.js') }}"></script>
</body>
</html>



