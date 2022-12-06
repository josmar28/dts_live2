@extends('layouts.app')
<link href="{{ asset('resources/assets/css/AdminLTE.min.css') }}" rel="stylesheet">

@section('content')

<?php
    use App\Http\Controllers\AdminController as Admin;
    use Illuminate\Support\Facades\Session;
    $totalAccepted = 0;
    $totalCreated = 0;
    $totalCycleend = 0;
    $totalOngoing = 0;
    $totalcypher = 0;
    $totalonper = 0;

$section = Session::get('auth')->section;

if(Session::get('auth')->user_priv==1)
{
    $accepted[] = Admin::allcountAccepted($section);
    $ongoing[] = Admin::allcountOngoing($section);
    $created[] = Admin::allcountCreated($section);
    $cycleend[] = Admin::allcountCycleEnd($section);


    foreach ($accepted as $accept)
    {
        $a = $accept;
    }

    foreach ($created as $create)
    {
        $c = $create;
    }

    foreach ($cycleend as $cycle)
    {
        $ce = $cycle;
    }
    foreach ($ongoing as $ongo)
    {
        $on = $ongo;
    }
    $totalAccepted += $a['first'];
    $totalCreated += $c['first'];
    $totalCycleend += $ce['first'];
    $totalOngoing += $on['first'];

}
else{
    $accepted = Admin::countAccepted($section);
    $created = Admin::countCreated($section);
    $cycleend = Admin::countCycleEnd($section);
    $ongoing = Admin::countOngoing($section);

    $totalAccepted += $accepted;
    $totalCreated += $created;
    $totalCycleend += $cycleend;
    $totalOngoing += $ongoing;

    $cyper =  $cycleend == 0 ? 0 : ($cycleend / $created)* 100;
                    
$cyper_val = number_format($cyper, 2);

$onper =  $ongoing == 0 ? 0 : ($ongoing / $created)* 100;

$onper_val = number_format($onper, 2);
}


// $cyper =  $cycleend == 0 ? 0 : ($cycleend / $created)* 100;
                    
// $cyper_val = number_format($cyper, 2);

// $onper =  $ongoing == 0 ? 0 : ($ongoing / $created)* 100;

// $onper_val = number_format($onper, 2);


$totalcypher = number_format($totalCycleend == 0 ? 0 : ($totalCycleend / $totalCreated) * 100,2);
$totalonper = number_format($totalOngoing == 0 ? 0 : ($totalOngoing / $totalCreated) * 100,2);

$newyear  = date("Y");
$month = date("n");
//Calculate the year quarter.
$quarter = ceil($month / 3);
?>
<div class="col-md-9 wrapper">

    <section class="content">
      @if( Session::get('auth')->user_priv==1 )
             <div class="container-fluid">
                <h2>Quarter Tabs</h2>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">1st</a></li>
                    <li><a data-toggle="tab" href="#menu1">2nd</a></li>
                    <li><a data-toggle="tab" href="#menu2">3rd</a></li>
                    <li><a data-toggle="tab" href="#menu3">4th</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                                  <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{$c['first']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Created</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-plus-circle" style="margin-top:10px;" ></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{$a['first']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Accepted</p>
                                            </div>
                                            <div class="icon small">
                                            <i class="fa fa-check-square" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">
                                            @if( Session::get('auth')->user_priv==1 )
                                            <div class="inner">
                                                <h3>{{$ce['first']}}<span class="sampleval"></span> </h3>
                                                <p>Total Cycle Ended</p>

                                                <p>In Region XII</p>
                                            </div>
                                            @else
                                            <div class="inner">
                                                <h3>{{$cycleend}}</h3>
                                                <p>({{$cyper_val}}% Done)</p>

                                                <p>Total Cycle Ended</p>
                                            </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-ban" style="margin-top:10px"> </i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-danger">
                                                @if( Session::get('auth')->user_priv==1 )
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/perongoinghome/1')}}" target="_blank">
                                                    <h3>{{$on['first']}} </h3>
                                                    <p>&nbsp;</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @else
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/ongoinghome/'.$section)}}" target="_blank">
                                                    <h3>{{$ongoing}} </h3>
                                                    <p>({{$onper_val}}%)</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-arrow-right" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        
                                        </div>  
                    </div>
                    <div id="menu1" class="tab-pane fade">
                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{$c['second']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Created</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-plus-circle" style="margin-top:10px;" ></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{$a['second']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Accepted</p>
                                            </div>
                                            <div class="icon small">
                                            <i class="fa fa-check-square" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">
                                            @if( Session::get('auth')->user_priv==1 )
                                            <div class="inner">
                                                <h3>{{$ce['second']}}<span class="sampleval"></span> </h3>
                                                <p>Total Cycle Ended</p>

                                                <p>In Region XII</p>
                                            </div>
                                            @else
                                            <div class="inner">
                                                <h3>{{$cycleend}}</h3>
                                                <p>({{$cyper_val}}% Done)</p>

                                                <p>Total Cycle Ended</p>
                                            </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-ban" style="margin-top:10px"> </i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-danger">
                                                @if( Session::get('auth')->user_priv==1 )
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/perongoinghome/2')}}" target="_blank">
                                                    <h3>{{$on['second']}} </h3>
                                                    <p>&nbsp;</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @else
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/ongoinghome/'.$section)}}" target="_blank">
                                                    <h3>{{$ongoing}} </h3>
                                                    <p>({{$onper_val}}%)</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-arrow-right" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        
                                        </div> 
                    </div>
                    <div id="menu2" class="tab-pane fade">
                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{$c['third']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Created</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-plus-circle" style="margin-top:10px;" ></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{$a['third']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Accepted</p>
                                            </div>
                                            <div class="icon small">
                                            <i class="fa fa-check-square" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">
                                            @if( Session::get('auth')->user_priv==1 )
                                            <div class="inner">
                                                <h3>{{$ce['third']}}<span class="sampleval"></span> </h3>
                                                <p>Total Cycle Ended</p>

                                                <p>In Region XII</p>
                                            </div>
                                            @else
                                            <div class="inner">
                                                <h3>{{$cycleend}}</h3>
                                                <p>({{$cyper_val}}% Done)</p>

                                                <p>Total Cycle Ended</p>
                                            </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-ban" style="margin-top:10px"> </i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-danger">
                                                @if( Session::get('auth')->user_priv==1 )
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/perongoinghome/3')}}" target="_blank">
                                                    <h3>{{$on['third']}} </h3>
                                                    <p>&nbsp;</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @else
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/ongoinghome/'.$section)}}" target="_blank">
                                                    <h3>{{$ongoing}} </h3>
                                                    <p>({{$onper_val}}%)</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-arrow-right" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        
                                        </div> 
                    </div>
                    <div id="menu3" class="tab-pane fade">
                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{$c['fourth']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Created</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-plus-circle" style="margin-top:10px;" ></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{$a['fourth']}}</h3>
                                                <p>&nbsp; </p>

                                                <p>Accepted</p>
                                            </div>
                                            <div class="icon small">
                                            <i class="fa fa-check-square" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">
                                            @if( Session::get('auth')->user_priv==1 )
                                            <div class="inner">
                                                <h3>{{$ce['fourth']}}<span class="sampleval"></span> </h3>
                                                <p>Total Cycle Ended</p>

                                                <p>In Region XII</p>
                                            </div>
                                            @else
                                            <div class="inner">
                                                <h3>{{$ce['fourth']}}</h3>
                                                <p>({{$cyper_val}}% Done)</p>

                                                <p>Total Cycle Ended</p>
                                            </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-ban" style="margin-top:10px"> </i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-danger">
                                                @if( Session::get('auth')->user_priv==1 )
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/perongoinghome/4')}}" target="_blank">
                                                    <h3>{{$on['fourth']}} </h3>
                                                    <p>&nbsp;</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @else
                                                <div class="inner">
                                                    <a href="{{ asset('documents/report/ongoinghome/'.$section)}}" target="_blank">
                                                    <h3>{{$ongoing}} </h3>
                                                    <p>({{$onper_val}}%)</p>
                                                                        </a>

                                                    <p>Ongoing</p>
                                                </div>
                                            @endif
                                            <div class="icon">
                                            <i class="fa fa-arrow-right" style="margin-top:10px"></i> 
                                            </div>
                                            <a href="#" class="small-box-footer"> </a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        
                                        </div> 
                    </div>
                </div>
            </div>
        @else
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$created}}</h3>
                <p>&nbsp; </p>

                <p>Created</p>
              </div>
              <div class="icon">
                <i class="fa fa-plus-circle" style="margin-top:10px;" ></i> 
              </div>
              <a href="#" class="small-box-footer"> </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">

                <h3>{{$accepted}}</h3>
                <p>&nbsp; </p>

                <p>Accepted</p>
              </div>
              <div class="icon small">
              <i class="fa fa-check-square" style="margin-top:10px"></i> 
              </div>
              <a href="#" class="small-box-footer"> </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$cycleend}}</h3>
                <p>({{$cyper_val}}% Done)</p>

                <p>Total Cycle Ended</p>
              </div>
              <div class="icon">
              <i class="fa fa-ban" style="margin-top:10px"> </i> 
              </div>
              <a href="#" class="small-box-footer"> </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <a href="{{ asset('documents/report/ongoinghome/'.$section)}}" target="_blank">
                    <h3>{{$ongoing}} </h3>
                    <p>({{$onper_val}}%)</p>
                                        </a>

                    <p>Ongoing</p>
                </div>
              <div class="icon">
              <i class="fa fa-arrow-right" style="margin-top:10px"></i> 
              </div>
              <a href="#" class="small-box-footer"> </a>
            </div>
          </div>
          <!-- ./col -->
          
        </div>
    </div>
@endif

    <div class="alert alert-jim">
        <h3 class="page-header">Created
            <small>Documents</small>
        </h3>
        <canvas id="createdDoc" width="400" height="200"></canvas>
        <h3 class="page-header">Accepted
            <small>Documents</small>
        </h3>
        <canvas id="acceptedDoc" width="400" height="200"></canvas>
    </div>
</div>
@include('sidebar')
<div class="modal fade" tabindex="-1" role="dialog" id="notificationModal" style="margin-top: 30px;z-index: 99999 ">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h3 style="font-weight: bold" class="text-success">WHAT'S NEW?</h3>
                <?php
                $dateNow = date('Y-m-d');
                ?>
                @if($dateNow==='2019-07-30')
                    <div class="alert alert-info">
                        <p class="text-info" style="font-size:1.3em;text-align: center;">
                            <strong>There will be a server maintenance TODAY (July 30, 2019) at 1:15PM to 02:00PM. Server optimization!</strong>
                        </p>
                    </div>
                @endif

                <div class="alert alert-success ">
                    <span class="text-success">
                        <i class="fa fa-info"></i>
                        Good day everyone! We would like to inform you that we will be encoding of Online PPMPV2.0 as soon as possible, just prepare your PPMP
                        <ul>
                            <li>Please be guided this ppmp master list as you start using the latest version</li>
                            <ul>
                                <li><a href="{{ asset('resources/ppmp_division/msd.pdf') }}" download>MSD PDF</a></li>
                                <li><a href="{{ asset('resources/ppmp_division/lhsd.pdf') }}" download>LHSD PDF</a></li>
                                <li><a href="{{ asset('resources/ppmp_division/rd_ard.pdf') }}" download>RD ARD PDF</a></li>
                                <li><a href="{{ asset('resources/ppmp_division/rled.pdf') }}" download>RLED PDF</a></li>
                            </ul>
                        </ul>
                        <h3 class="text-center" style="color: #2f8030">Thank you! &#128512;</h3>
                    </span>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection

@section('js')
@include('modal.pendinginci')
@include('modal.announcement1')

<script src="{{ asset('resources/plugin/Chart.js/Chart.min.js') }}"></script>
<script>
 
   $( ".quarter" ).change(function() {
    var val = $(this).val();

    alert(val);
    });

    //$('#notificationModal').modal('show');
    <?php echo 'var url = "'.asset('home/chart').'";';?>
    var jim = [];
    $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
            jim = jQuery.parseJSON(data);
            //chart created docs
            var ctx = document.getElementById("createdDoc");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jim.data1.months,
                    datasets: [{
                        label: '# of Created Documents',
                        data: jim.data1.count,
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            //end chart created docs
            //chart accepted docs
            var ctx = document.getElementById("acceptedDoc");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jim.data2.months,
                    datasets: [{
                        label: '# of Accepted Documents',
                        data: jim.data2.count,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            //end chart accepted docs
            $('.loading').hide();
        }
    });
</script>
@endsection

