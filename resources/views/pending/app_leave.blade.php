<?php
use Illuminate\Support\Facades\Session;
use App\Users;
use App\Section;
$documents = Session::get('receivedDocuments');
?>
<html>
<title>Print Logs</title>
<head>
    <link href="{{ asset('resources/assets/css/print.css') }}" rel="stylesheet">
</head>
<body>
<table class="letter-head" cellpadding="0" cellspacing="0">
    <tr>
        <td width="20%"><center><img src="{{ asset('resources/img/doh.png') }}" width="100"></center></td>
        <td width="60%">
            <center>
                <strong>Republic of the Philippines</strong><br>
                Department of Health - Regional Office 7<br>
                <h4 style="margin:0;">DOCUMENT TRACKING SYSTEM LOGS</h4>
                (Delivered Documents)<br>
                {{ date('M d, Y',strtotime(Session::get('startdate'))) }} - {{ date('M d, Y',strtotime(Session::get('enddate'))) }}
            </center>
        </td>
        <td width="20%"><center><img src="{{ asset('resources/img/ro7.png') }}" width="100"></center></td>
    </tr>

</table>
<br>
<center><h3>{{ strtoupper(Session::get('doc_type')) }}</h3></center>
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Date Delivered</th>
        <th>Receive from</th>
        <th>Route # / Remarks</th>
        <th>Applicant Name</th>
        <th>Days leave</th>
    </tr>
    </thead>
    <tbody>
    @foreach($documents as $doc)
        <tr>
            <td>
                {{ date('M d, Y',strtotime($doc->date_in)) }}<br>
                {{ date('h:i:s A',strtotime($doc->date_in)) }}
            </td>
            <td>
                <?php $user = Users::find($doc->received_by);?>
                {{ $user->fname }}
                {{ $user->lname }}
                <br>
                <em>({{ Section::find($user->section)->description }})</em>
            </td>
            <td>
                Route No: {{ $doc->route_no }}<br>
                {!! nl2br($doc->description) !!}
            </td>
            <td>{{ \App\Tracking::where('route_no',$doc->route_no)->pluck('cdo_applicant')->first()  }}</td>
            <td>{{ \App\Tracking::where('route_no',$doc->route_no)->pluck('cdo_day')->first()  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>