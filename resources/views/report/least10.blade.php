<?php
    use App\Http\Controllers\SectionController as Sec;
    use App\Http\Controllers\AdminController as Admin;

?>
@extends('layouts.app')

@section('content')


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

        <h2 class="page-header">CHD12 Least 10 Report</h2>
    <form class="form-inline" method="POST" action="{{ asset('chd12report/least10') }}" onsubmit="return searchDocument()">
           {{ csrf_field() }}

          <div class="form-group">
             <div class="input-group">
                        <select name="year" id="newyear" class="form-control">
                        <option value ="">Select...</option>
                        @for($year=2018;$year<=date('Y');$year++)
                                <option {{ ($newyear == $year ? 'selected' : '') }}  value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                </div>
                 <div class="input-group" >
                   <select name="quarter" class="chosen-select-static" style="width:150px">
                   <option value="0">Select Quarter</option>
                   <option <?php if($quarter=='1') echo 'selected';?> value="1">1st Quarter</option>
                   <option <?php if($quarter=='2') echo 'selected';?> value="2">2nd Quarter</option>
                   <option <?php if($quarter=='3') echo 'selected';?> value="3">3rd Quarter</option>
                   <option <?php if($quarter=='4') echo 'selected';?> value="4">4th Quarter</option>

                </select>
                </div>

                <div class="input-group" >
                   <select name="type" class="chosen-select-static" style="width:150px">
                   <option value="0">Select Type</option>
                   <option <?php if($type=='reported') echo 'selected';?> value="reported">45 minutes</option>
                   <option <?php if($type=='duration') echo 'selected';?> value="duration">SOP based</option>
                </select>
                </div>
            
                <button type="submit" class="btn btn-success" onclick="checkDocTye()"><i class="fa fa-search"></i> Filter</button>
                 <input type="button" class="btn btn-info" value="Print this page" onClick="printReport()">
         </div>
         <hr>
         <div id="reportPrinting">
            <table class="table table-bordered table-hover" style="border: 3px solid #d6e9c6">
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
                @foreach($least10 as $data)
                    <tr>
                       <td class="col-sm-2">{{ $data->section }}</td>
                       <td class="col-sm-2" style="text-align:center">{{ $data->division }}</td>
                       <td class="col-sm-2" style="text-align:center">{{ $data->reported }}</td>
                    </tr>   
                @endforeach
               </tbody>
            </table>
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
        var prtContent = document.getElementById("reportPrinting");
        var WinPrint = window.open();
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
     function searchDocument(){
            $('.loading').show();
            setTimeout(function(){
                return true;
            },2000);
        }
</script>

@endsection
@section('plugin')

@endsection

@section('css')

@endsection

