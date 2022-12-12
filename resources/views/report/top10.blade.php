<?php
    use App\Http\Controllers\SectionController as Sec;
    use App\Http\Controllers\AdminController as Admin;

?>
@extends('layouts.app')

@section('content')
<style>
    .upper, .info, .table {
        width: 100%;
    }   
    .upper td, .info td, .table td, thead th{
        border:1px solid #000;
    }
    .upper td {
        padding:10px;
    }
    .table th {
        border:1px solid #000;
    }
    .table td {
        padding: 5px;
        vertical-align: top;
    }
@media print {
    
  body * {
    visibility: hidden;
  }
  #section-to-print, #section-to-print * {
    visibility: visible;

  }
  #section-to-print {
    position: absolute;
    left: 0;
    top: 0;
    font-size:11px;
  }
  #section-to-print #print_header{
display:block!important;
margin-bottom: 50px;
font-size:15px;
}
  #header, #nav, .noprint
    {
     display: none;
    }
}
</style>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alert alert-jim" id="inputText">

        <h2 class="page-header">CHD12 TOP 10 Report</h2>
    <form class="form-inline" method="POST" action="{{ asset('chd12report/top10') }}" onsubmit="return searchDocument()">
           {{ csrf_field() }}

          <div class="form-group">
             <div class="input-group">
             <input type="text" class="form-control" id="reservation" name="daterange" value="@if(isset($daterange)){{ $daterange }}@endif" placeholder="Input date range here..." >
                </div>

                <div class="input-group" >
                   <select name="type" class="chosen-select-static" style="width:150px">
                   <option value="0">Select Type</option>
                   <option <?php if($type=='reported') echo 'selected';?> value="reported">45 minutes</option>
                   <option <?php if($type=='duration') echo 'selected';?> value="duration">SOP based</option>
                </select>
                </div>
            
                <button type="submit" class="btn btn-success" onclick="checkDocTye()"><i class="fa fa-search"></i> Filter</button>
                 <input type="button" class="btn btn-info" value="Print this page" onclick="window.print()">
         </div>
         <hr>
         <div id="section-to-print">
              <table class="upper" cellpadding="1" cellspacing="1" style="display:none" id="print_header">
                    <tr>
                        <td width="10%"><center><img src="{{ asset('resources/img/doh.png') }}" /></center></td>
                        <td width="60%" style="font-size: 11pt;">
                            <center>
                                <strong>Republic of the Philippines</strong><br>
                                DEPARTMENT OF HEALTH<br>
                                <strong>CENTER FOR HEALTH DEVELOPMENT <br> SOCCSKSARGEN Region</strong><br>
                            </center>
                        </td>
                        <td width="10%"><center><img src="{{ asset('resources/img/f1.jpg') }}" /></center></td>
                    </tr>

                </table>
            <h4 id="title_print">DOCUMENT TRACKING SYSTEM (DTS)<br>{{$title}}</h4>
            <table class="upper">
                <thead>
                    <tr>
                        <th colspan="12" class="bg-success text-bold text-success text-uppercase" style="padding: 15px 10px;"></th>
                    </tr>
                    <tr>
                        <th class="col-sm-3">Section</th>
                        <th class="col-sm-4" style="text-align:center">Division</th>
                    @if($type == 'duration')
                        <th class="col-sm-4" style="text-align:center">Documents Lapsed</th>
                    @else
                    <th class="col-sm-4" style="text-align:center">Documents Reported</th>
                    @endif
                    </tr>
                   
                </thead>
                <tbody>
                @foreach($top10 as $data)
                    <tr>
                       <td class="col-sm-2">{{ $data->section }}</td>
                       <td class="col-sm-2" style="text-align:left">{{ $data->division }}</td>
                       <td class="col-sm-2" style="text-align:center">{{ $data->reported }}</td>
                    </tr>   
                @endforeach
               </tbody>
            </table>
                <div id="footer_print" class="row" style ="margin-top:50px; visibility:hidden">
                    <div class="col-xs-6">
                         <h5>Prepared by:<br><br><br><br> <center><u> Josmar Del Poso </u><br><span>Computer Programmer I</span></center></h5>
                    </div>   
                    <div class="col-xs-6">
                        <h5>Checked and reviewed by:<br><br><br><br> <center><u> Garizaldy Epistola </u><br><span>Computer Maintenance Technologist III</span></center></h5>
                    </div>  
                   
                </div>
               </div>   
             
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        <div class="alert alert-danger error hide">
            <i class="fa fa-warning"></i> Please select Document Type!
        </div>
    </div>
</div>
<script type="text/javascript">

    function printReport()
    {
        document.getElementById("print_header").style.display = "block";
    }
     function searchDocument(){
            $('.loading').show();
            setTimeout(function(){
                return true;
            },2000);
        }
</script>
@endsection
@section('js')
@include('js.return')
@endsection
@section('plugin')

@endsection

@section('css')

@endsection

