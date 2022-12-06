<?php
use Illuminate\Support\Facades\Session;
use App\Users;
use App\Section;
use App\Http\Controllers\DocumentController as Doc;
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
                RECEIVED DOCUMENTS<br>
                <h4 style="margin:0;">DOCUMENT TRACKING SYSTEM LOGS</h4>
                ({{ Section::find(Session::get('auth')->section)->description }})<br>
                {{ Session::get('auth')->fname }} {{ Session::get('auth')->lname }}<br>
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
        <th>Document Type</th>
        <th>Date Received</th>
        <th>Received From</th>
        <th>Route # / Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($documents as $doc)
        <tr>
            <td>
                {{ Doc::docTypeName($doc->doc_type) }}
            </td>
            <td>
                {{ date('M d, Y',strtotime($doc->date_in)) }}<br>
                {{ date('h:i:s A',strtotime($doc->date_in)) }}
            </td>
            <td>
                <?php $user = Users::find($doc->delivered_by);?>
                {{ $user->fname }}
                {{ $user->lname }}
                <br>
                <em>({{ Section::find($user->section)->description }})</em>
            </td>
            <td>
                Route No: {{ $doc->route_no }}<br>
                {!! nl2br($doc->action) !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>