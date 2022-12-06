<?php
    use App\Http\Controllers\SectionController as Sec;
    use App\Http\Controllers\AdminController as Admin;


    $year = Session::get('year_session');
    $month = Session::get('month_session');


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

        <h2 class="page-header">CHD12 Report</h2>
    <form class="form-inline" method="POST" action="{{ asset('chd12report/search') }}" onsubmit="return searchDocument()">
           {{ csrf_field() }}

          <div class="form-group">
            
              <div class="input-group">
                <select name="year" class="chosen-select-static"  style="width:150px">
                   <option value="0">Select Year</option>
                   <option <?php if($year=='2021') echo 'selected';?> value="2021">2021</option>
                   <option <?php if($year=='2022') echo 'selected';?> value="2022">2022</option>
                   <option <?php if($year=='2023') echo 'selected';?> value="2023">2023</option>
                   <option <?php if($year=='2024') echo 'selected';?> value="2024">2024</option>
                   <option <?php if($year=='2025') echo 'selected';?> value="2025">2025</option>
                </select>
                 </div>
                 <div class="input-group" >
                <select name="month" class="chosen-select-static" style="width:150px">
                   <option value="0">Select Month</option>
                   <option <?php if($month=='1') echo 'selected';?> value="1">January</option>
                   <option <?php if($month=='2') echo 'selected';?> value="2">February</option>
                   <option <?php if($month=='3') echo 'selected';?> value="3">March</option>
                   <option <?php if($month=='4') echo 'selected';?> value="4">April</option>
                   <option <?php if($month=='5') echo 'selected';?> value="5">May</option>
                   <option <?php if($month=='6') echo 'selected';?> value="6">June</option>
                   <option <?php if($month=='7') echo 'selected';?> value="7">July</option>
                   <option <?php if($month=='8') echo 'selected';?> value="8">August</option>
                   <option <?php if($month=='9') echo 'selected';?> value="9">September</option>
                   <option <?php if($month=='10') echo 'selected';?> value="10">October</option>
                   <option <?php if($month=='11') echo 'selected';?> value="11">November</option>
                   <option <?php if($month=='12') echo 'selected';?> value="12">December</option>
                </select>
                </div>
            
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
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
                        <th class="col-sm-2">Section</th>
                        <th class="col-sm-2" style="text-align:center">Created</th>
                        <th class="col-sm-2" style="text-align:center">Accepted</th>
                        <th class="col-sm-2" style="text-align:center">Reported</th>
                         <th class="col-sm-2" style="text-align:center">Waiting</th>
                         <th class="col-sm-2" style="text-align:center">Cycle Ended</th>
                        <th class="col-sm-2" style="text-align:center">Year</th>
                        <th class="col-sm-2" style="text-align:center">Month</th>
                    </tr>
                   
                </thead>
                <tbody>
                    @foreach($chd12_report as $chd)
                    <?php           

                    Session::put('report_year1',$year);
                    Session::put('report_month1',$month);

                       $cycleend = Admin::countCycleEnd1($chd->ids);
                    ?>
                    <tr>
                       <td class="col-sm-2">{{$chd -> section}}</td>
                       @if( $chd -> created > 0)    
                         <td class="col-sm-2" style="text-align:center"> <a href="{{ asset('documents/report/allcreated/'.$chd -> ids.'/'.$year.'/'.$month)}}" target="_blank">{{$chd -> created}}</a></td>
                         @else
                         <td class="col-sm-2" style="text-align:center">{{$chd -> created}}</td>
                         @endif
                         @if($chd -> accepted > 0) 
                       <td class="col-sm-2" style="text-align:center"> <a href="{{ asset('documents/report/allaccepted/'.$chd -> ids.'/'.$year.'/'.$month)}}" target="_blank">{{$chd -> accepted}}</a></td>
                        @else
                        <td class="col-sm-2" style="text-align:center">{{$chd -> accepted}}</td>
                        @endif
                        @if($chd -> reported > 0) 
                       <td class="col-sm-2" style="text-align:center"> <a href="{{ asset('documents/report/allreported/'.$chd -> ids.'/'.$year.'/'.$month)}}" target="_blank">{{$chd -> reported}}</a></td>
                       @else
                       <td class="col-sm-2" style="text-align:center">{{$chd -> reported}}</td>
                       @endif
                       @if($chd -> waiting > 0) 
                       <td class="col-sm-2" style="text-align:center"> <a href="{{ asset('documents/report/allwaiting/'.$chd -> ids.'/'.$year.'/'.$month)}}" target="_blank">{{$chd -> waiting}}</a></td>
                      @else
                      <td class="col-sm-2" style="text-align:center">{{$chd -> waiting}}</td>
                      @endif
                      @if($cycleend > 0) 
                       <td class="col-sm-2" style="text-align:center"> <a href="{{ asset('documents/report/allcycleend/'.$chd -> ids.'/'.$year.'/'.$month)}}" target="_blank">{{$cycleend}}</a></td>
                      @else
                      <td class="col-sm-2" style="text-align:center">{{$cycleend}}</td>
                      @endif
                       <td class="col-sm-2" style="text-align:center"> {{$chd -> years}}</td>
                       <td class="col-sm-2" style="text-align:center"> {{$chd -> months}}</td>
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

