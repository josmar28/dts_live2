<?php
use Illuminate\Support\Facades\Session;
use App\Users;
use App\Section;
use App\Release;
use App\Http\Controllers\DocumentController as Doc;
use Illuminate\Support\Facades\Input;

$type = Input::get('type');
if($type=='section'){
    $documents = Session::get('logsDocument');
}else{
    $documents = Doc::printLogsDocument();
}

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
        <td width="20%"><center><img src="{{ asset('public/img/dohro12logo_100RES.png') }}" width="100"></center></td>
    </tr>

</table>
<br>
<center><h3>{{ strtoupper(Session::get('doc_type')) }}</h3></center>
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th width="29%">Route # / Remarks</th>
        <th width="12%">Received Date</th>
        <th width="12%">Received From</th>
        <th width="12%">Released Date</th>
        <th width="12%">Released To</th>
        <th width="20%">Document Type</th>
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
                <?php 
                    if($user = Users::find($doc->delivered_by)){
                        $del_fname = $user->fname;
                        $del_lname = $user->lname;
                        $del_section = Section::find($user->section)->description;
                    } else {
                        $del_fname = "No Firstname";
                        $del_lname = "No Lastname";
                        $del_section = "No Section";
                    }
                ?>
                {{ $del_fname }}
                {{ $del_lname }}
                <br>
                <em>({{ $del_section }})</em>
            </td>
            <?php
            $out = Doc::deliveredDocument($doc->route_no,$doc->received_by,$doc->doc_type);
            ?>
            @if($out)
                <td>{{ date('M d, Y',strtotime($out->date_in)) }}<br>{{ date('h:i:s A',strtotime($out->date_in)) }}</td>
                <td>
                    <?php 
                        if($user = Users::find($out->received_by)){
                            $rec_fname = $user->fname;
                            $rec_lname = $user->lname;
                            $rec_section = Section::find($user->section)->description;
                        } else {
                            $rec_fname = "No Firstname";
                            $rec_lname = "No Lastname";
                            $rec_section = "No Section";
                        }
                    ?>
                    {{ $rec_fname }}
                    {{ $rec_lname }}
                    <br>
                    <em>({{ $rec_section }})</em>
                </td>
            @else
                <?php $rel = Release::where('route_no', $doc->route_no)->first(); ?>
                @if($rel)
                    <td class="text-info">
                        {{ date('M d, Y',strtotime($rel->date_reported)) }}<br>
                        {{ date('h:i:s A',strtotime($rel->date_reported)) }}<br>
                    </td>
                    <td class="text-info">
                        {{ Section::find($rel->section_id)->description }}
                    </td>
                @else
                    <td></td>
                    <td></td>
                @endif
            @endif
            <td>{{ \App\Http\Controllers\DocumentController::docTypeName($doc->doc_type) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
