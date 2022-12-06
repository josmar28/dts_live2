<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Users;
use App\Section;
use App\Http\Controllers\AccessController as Access;
use App\Http\Controllers\DocumentController as Doc;

$access = Access::access();
use Illuminate\Support\Facades\Input;

$type = Input::get('type');
if($type=='section'){
    $documents = Session::get('logsDocument');
}else{
    $documents = Doc::printLogsDocument();
}
$section = Session::get('auth')->section;
?>
<html>
<title>Print Logs</title>
<head>
    <link href="{{ asset('resources/assets/css/print.css') }}" rel="stylesheet">
    <style>
        html {
            font-size:0.8em;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body>
<table class="letter-head" cellpadding="0" cellspacing="0">
    <tr>
        <td width="20%"><center><img src="{{ asset('public/img/doh.png') }}" width="100"></center></td>
        <td width="60%">
            <center>
                <h4 style="margin:0;">DOCUMENT TRACKING SYSTEM LOGS</h4>
                ({{ Section::find(Session::get('auth')->section)->description }})<br>
                {{ Session::get('auth')->fname }} {{ Session::get('auth')->lname }}<br>
                {{ date('M d, Y',strtotime(Session::get('startdate'))) }} - {{ date('M d, Y',strtotime(Session::get('enddate'))) }}
            </center>
        </td>
        <td width="20%"><center><img src="{{ asset('public/img/ro7.png') }}" width="100"></center></td>
    </tr>

</table>
<br>
<center><h3>{{ strtoupper(Session::get('doc_type')) }}</h3></center>
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Route # / Remarks</th>
        <th>Received Date</th>
        <th>Received From</th>
        <th>Released Date</th>
        <th>Released To</th>
        @if($access=='accounting')
        <th>DV #</th>
        @endif
        @if($access=='budget')
        <th>ORS #</th>
        <th>Fund Source</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($documents as $doc)
    <tr>
        <td>
            {{ $doc->route_no }}
            <br>
            {!! nl2br($doc->description) !!}
        </td>
        <td>{{ date('M d, Y',strtotime($doc->date_in)) }}<br>{{ date('h:i:s A',strtotime($doc->date_in)) }}</td>
        <td>
            <?php $user = Users::find($doc->delivered_by);?>
            {{ $user->fname }}
            {{ $user->lname }}
            <br>
            <em>({{ Section::find($user->section)->description }})</em>
        </td>
        <?php
        $out = Doc::deliveredDocument($doc->route_no,$doc->received_by,$doc->doc_type);
        ?>
        @if($out)
        <td>{{ date('M d, Y',strtotime($out->date_in)) }}<br>{{ date('h:i:s A',strtotime($out->date_in)) }}</td>
        <td>
            <?php $user = Users::find($out->received_by);?>
            {{ $user->fname }}
            {{ $user->lname }}
            <br>
            <em>({{ Section::find($user->section)->description }})</em>
        </td>
        @else
        <td></td>
        <td></td>
        @endif
        @if($access=='accounting')
        <td>{{ $doc->dv_no }}</td>
        @endif
        @if($access=='budget')
        <td>{{ $doc->ors_no }}</td>
        <td>{{ $doc->fund_source_budget }}</td>
        @endif
    </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>