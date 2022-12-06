<?php
    use App\Http\Controllers\SectionController as Sec;
    use App\Http\Controllers\AdminController as Admin;

    $year = Session::get('report_year');
    $month = Session::get('report_month');
?>
@extends('layouts.app')
<style>
    @media print {
  #printPageButton {
    display: none;
  }
  .input-group {
    visibility: hidden;
  }
  #month {
    appearance: none;
	padding: 5px;
	background-color: #4834d4;
	color: white;
	border: none;
	font-family: inherit;
	outline: none;
  }
  #search {
    display: none;
  }
table,thead,tbody,tr,td,th {
    border-left: 1px solid #000;
    border-right: 1px solid #000;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}

}
    </style>
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
        <h2 class="page-header">Print Report</h2>
        <form class="form-inline" method="POST" action="{{ asset('document/report/dates') }}" onsubmit="return searchDocument()">
            {{ csrf_field() }}
            <div class="form-group">
            
            <div class="input-group">
              <select name="year" id="year" class="chosen-select-static"  style="width:150px" required>
                 <option value="0">Select Year</option>
                 <option <?php if($year=='2021') echo 'selected';?> value="2021">2021</option>
                 <option <?php if($year=='2022') echo 'selected';?> value="2022">2022</option>
                 <option <?php if($year=='2023') echo 'selected';?> value="2023">2023</option>
                 <option <?php if($year=='2024') echo 'selected';?> value="2024">2024</option>
                 <option <?php if($year=='2025') echo 'selected';?> value="2025">2025</option>
              </select>
               </div>
               <div class="input-group" >
              <select name="month" id="month" class="chosen-select-static" style="width:150px">
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
</div>
           
    <button type="submit" id="search" class="btn btn-success" onclick="checkDocTye()"><i class="fa fa-search"></i> Filter</button>
            @foreach($division as $div)
            <?php
                $sections = Sec::getSections($div->id);
                $totalAccepted = 0;
                $totalCreated = 0;
                $totalCycleend = 0;
                $totalOngoing = 0;
                $totalcypher = 0;
                $totalonper = 0;
            ?>
            <table class="table table-striped table-hover" style="border: 1px solid #000">
                <thead>
                    <tr>
                        <th colspan="7" class="bg-success text-bold text-success text-uppercase" style="padding: 15px 10px;">{{ $div->description }}</th>
                    </tr>
                    <tr>
                        <th>Sections</th>
                        <th>Created Documents</th>
                        <th>Accepted Documents</th>
                        <th>Cycle ended Documents</th> 
                        <th>Cycle ended %</th> 
                        <th>Ongoing Documents </th>
                        <th>Ongoing %</th>  
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $sect)
                    <?php
                        $accepted = Admin::countAccepted($sect->id);
                        $created = Admin::countCreated($sect->id);
                        $cycleend = Admin::countCycleEnd($sect->id);
                        $ongoing = Admin::countOngoing($sect->id);

                        $cyper =  $cycleend == 0 ? 0 : ($cycleend / $created)* 100;
                    
                        $cyper_val = number_format($cyper, 2);

                        $onper =  $ongoing == 0 ? 0 : ($ongoing / $created)* 100;

                        $onper_val = number_format($onper, 2);

                        $totalAccepted += $accepted;
                        $totalCreated += $created;
                        $totalCycleend += $cycleend;
                        $totalOngoing += $ongoing;
                        $totalcypher = number_format($totalCycleend == 0 ? 0 : ($totalCycleend / $totalCreated) * 100,2);
                        $totalonper = number_format($totalOngoing == 0 ? 0 : ($totalOngoing / $totalCreated) * 100,2);
                    ?>
                    <tr>
                        <td>{{ $sect->description }}</td>
                        <td>
                            @if($created==0)
                                Nothing
                            @elseif($created==1)
                                1  
                            @else
                            {{ $created}}
                            @endif
                        </td>
                        <td>
                            @if($accepted==0)
                                Nothing
                            @elseif($accepted==1)
                                1 
                            @else
                                {{ $accepted }} 
                            @endif
                        </td>
                        <td>
                        @if($cycleend==0)
                                Nothing
                            @elseif($cycleend==1)
                                1 
                            @else
                                {{ $cycleend }} 
                            @endif
                        </td>
                        <td>
                        {{$cyper_val}} %
                        </td>
                        <td>
                        @if($ongoing==0)
                                Nothing
                            @else
                            <a href="{{ asset('documents/report/ongoingbody/'.$sect->id)}}" target="_blank">
                                       {{ $ongoing}}
                                    </a>
                            @endif
                        </td>
                        <td>
                        {{$onper_val}} %
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="bg-warning text-bold text-uppercase">TOTAL</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalCreated }}</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalAccepted }}</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalCycleend }}</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalcypher }} %</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalOngoing }}</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalonper }} %</td>
                    </tr>
                </tbody>
            </table>
            @endforeach
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        <div class="alert alert-danger error hide">
            <i class="fa fa-warning"></i> Please select Document Type!
        </div>
    </div>
@endsection

<script>
    
    function ongoingBody(sec_id){
        console.log(sec_id);
        var url = "<?php echo asset('documents/report/ongoingbody'); ?>";
        var json = {
            "sec_id" : sec_id,
            "_token" : "<?php echo csrf_token(); ?>"
        };
        $.post(url,json,function(result){
            $(".ongoing_body").html(result);
        });
    }
</script>
@section('plugin')

@endsection

@section('css')

@endsection

