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

        <h2 class="page-header">Section Duration / Document</h2>
    <form class="form-inline" method="POST" action="{{ asset('chd12report/secDuration') }}" onsubmit="return searchDocument()">
           {{ csrf_field() }}
           <div class="form-group">
            <?php
                $sections = App\Section::all();
                ?>
                  @if( (Session::get('auth')->user_priv==1 || Session::get('auth')->username=='2002000972') ||  Session::get('auth')->section == 82 || Session::get('auth')->section == 100)
                        
                 <div class="input-group">
                <select name="section" id="section" class="form-control">
                <option value ="">Select...</option>
                        @foreach($sections as $sec)
                        <option {{ ($cur_section == $sec->id ? 'selected' : '') }} value="{{ $sec->id }}">{{ $sec->description }}</option>
                        @endforeach
                </select>
                </div>
                <div class="input-group">
                        <select name="newyear" id="newyear" class="form-control">
                        <option value ="">Select...</option>
                        @for($year=2018;$year<=date('Y');$year++)
                                <option {{ ($newyear == $year ? 'selected' : '') }}  value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                </div>
                @else
                <div class="input-group">
                <select name="section" id="section" class="form-control" disabled>
                <option value ="">Select...</option>
                        @foreach($sections as $sec)
                        <option {{ ($cur_section == $sec->id ? 'selected' : '') }} value="{{ $sec->id }}">{{ $sec->description }}</option>
                        @endforeach
                </select>
                </div>
                      
                <div class="input-group">
                        <select name="newyear" id="newyear" class="form-control" disabled>
                        <option value ="">Select...</option>
                        @for($year=2018;$year<=date('Y');$year++)
                                <option {{ ($newyear == $year ? 'selected' : '') }}  value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                        </div>
                        @endif
            </div>

               <button type="submit" id="filter" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
               <!-- <a href="{{ asset('chd12report/aveLogs')}}" class="btn btn-info form-control">
                        View All</a> -->
          <!-- <div class="form-group">
            
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
                   <option <?php if($month=='
                   \7') echo 'selected';?> value="7">July</option>
                   <option <?php if($month=='8') echo 'selected';?> value="8">August</option>
                   <option <?php if($month=='9') echo 'selected';?> value="9">Septmber</option>
                   <option <?php if($month=='10') echo 'selected';?> value="10">October</option>
                   <option <?php if($month=='11') echo 'selected';?> value="11">November</option>
                   <option <?php if($month=='12') echo 'selected';?> value="12">December</option>
                </select>
                </div>
            
                <button type="submit" class="btn btn-success" onclick="checkDocTye()"><i class="fa fa-search"></i> Filter</button>
                 <input type="button" class="btn btn-info" value="Print this page" onClick="printReport()">
             </div> -->
         <div id="reportPrinting">
            <table class="table table-bordered table-hover" style="border: 3px solid #d6e9c6">
                <thead>
                    <tr>
                        <th colspan="12" class="bg-success text-bold text-success text-uppercase" style="padding: 15px 10px;"></th>
                    </tr>
                    <tr>
                        <th class="col-sm-3" style="text-align:center">Document Details</th>
                        <th class="col-sm-2" style="text-align:center">No of Documents</th>
                        <th class="col-sm-2" style="text-align:center">Average Duration</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $row)
                <?php
                if($row->doc_type == 'APP_LEAVE')
                {
                    $doc_type = 'Application for Leave';
                }elseif($row->doc_type == 'BILLS')
                {
                    $doc_type = 'Bills';
                } 
                elseif($row->doc_type == 'GENERAL')
                {
                    $doc_type = 'General Documents';
                } 
                elseif($row->doc_type == 'INCOMING')
                {
                    $doc_type = 'Incoming Mails';
                } 
                 elseif($row->doc_type == 'OFFICE_ORDER')
                {
                    $doc_type = 'Office Order';
                } 
                elseif($row->doc_type == 'PO')
                {
                    $doc_type = 'Purchase Order';
                } 
                elseif($row->doc_type == 'PRC')
                {
                    $doc_type = 'Purchase Request - Cash Advance';
                } 
                elseif($row->doc_type == 'PRR_S')
                {
                    $doc_type = 'Purchase Request - Regular';
                } 
                elseif($row->doc_type == 'ROUTE')
                {
                    $doc_type = 'Routing Slip';
                } 
                elseif($row->doc_type == 'SAL')
                {
                    $doc_type = 'Salary, Honoria, Remittances';
                } 
                elseif($row->doc_type == 'TEV')
                {
                    $doc_type = 'Travel Expenses Voucher';
                } 
                else{
                    $doc_type = 'Not Define';
                }
                
                if($row->minutes_duration > 60 && $row->hours_duration > 24)
                {
                    $duration = $row->days_duration.' days';
                } elseif($row->minutes_duration > 60 && $row->hours_duration < 24 )
                {
                    $duration = $row->hours_duration.' hours';
                }
                else
                {
                    $duration = $row->minutes_duration.' minutes';
                }
                ?>
                    <tr>
                       <td class="col-sm-2" style="text-align:center">{{$doc_type}}</td>
                       <td class="col-sm-2" style="text-align:center">{{$row->noofdocs}}</td>
                       <td class="col-sm-2" style="text-align:center">{{$duration}}</td>
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

