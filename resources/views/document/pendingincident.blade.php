<?php
    use App\Tracking_Details;
    //print_r($data);
    $daterange = Session::get('range');

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
    <style>
        .action .btn{
            width:100%;
            margin-bottom: 5px;
        }
    </style>
    <h2 class="page-header">Pending Incident Logs</h2>
    <form class="form-inline" method="POST" action="{{ asset('document/pending/incident') }}" onsubmit="return searchDocument();" id="searchForm">
    <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                </div>
                <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="" autofocus>
                           <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
             </div>
         {{ csrf_field() }}   
           
        </div>
        </form>
    <div class="clearfix"></div>
    <div class="page-divider"></div>
    @if(count($data))
    <div class="table-responsive">
        <table class="table table-list table-hover table-striped">
            <thead>
                <tr>
                     <th width="8%">Actions</th>
                    <th width="25%">Route # / Subject</th>
                    <th>Incident / Reason</th>
                    <th>Released Date</th>
                    <th>Date In</th>
                    <th>Remarks</th>
                    <th style="text-align:center">Status</th>  
                    
                </tr>
            </thead>
            <tbody>
            @foreach($data as $dataa)
                   <tr>
                    <td>
                        <a href="#track" data-link="{{ asset('document/track/'.$dataa->route_no) }}" data-route="{{ $dataa->route_no }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a>
                        <br /><br /> 
                     <a href="#incident" data-link="{{ asset('chd12report/incident/'.$dataa->id)}}" data-route="{{ $dataa->route_no }}" data-description ="{{ $dataa->description }}"  data-toggle="modal" class="btn btn-sm btn-info col-sm-12"><i class="fa fa-pencil"></i> Add Incident</a>
                 </td>
                 <td>
                            <a class="title-info" data-route="{{ $dataa->route_no }}" data-link="{{ asset('/document/info/'.$dataa->route_no) }}" href="#document_info" data-toggle="modal">{{ $dataa->route_no }}</a>
                            <br>
                            {!! nl2br($dataa->description) !!}
                        </td>
                 <td>  <b>{{$dataa -> incident_type}} </b><br />
                               {{$dataa -> reason}}
                        </td>
                     
                        <td>{{ date('M d, Y',strtotime($dataa -> released_date)) }}<br>{{ date('h:i:s A',strtotime($dataa -> released_date)) }}</td>
                        <td>{{ date('M d, Y',strtotime($dataa -> date_in)) }}<br>{{ date('h:i:s A',strtotime($dataa -> date_in)) }}</td>
                       <td>{!! nl2br($dataa -> remarks) !!}</td> 
                       <td style="text-align:center">{{$dataa -> status}}</td>
                    </tr>
             @endforeach
            
            </tbody>
        </table>
        {{ $data->links() }}
    </div>
    @else
        <div class="alert alert-danger">
            <strong><i class="fa fa-times fa-lg"></i> No documents found! </strong>
        </div>
        @endif
   
</div>

@include('modal.release_modal')
@include('modal.prr_supply_modal')
        
@endsection
<script>  $("#penModal").modal('hide');</script>
@section('plugin_old')

@include('js.release_js')
<script>
  

    $('#reservation').daterangepicker();
        $('.chosen-select').chosen();

    function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },2000);
    }

    $("a[href='#incident']").on('click',function(){
        $('.track_history1').html(loadingState);
        var route_no = $(this).data('route');
        var url2 = $(this).data('link');
        var desc = $(this).data('description');
        $('#track_route_no1').val('Loading...');
        setTimeout(function(){
            $('#track_route_no1').val(route_no);
            $.ajax({
                url: url2,
                type: 'GET',
                success: function(data) {
                    console.log(url2);
                    $('.track_history1').html(data);
                    $('textarea#inci_subject').val(desc);
                }
            });
        },1000);
    
    });

    @if(Session::get('add_inci'))
        Lobibox.notify('success', {
            title: "",
            msg: "<?php echo Session::get("add_inci_message"); ?>",
            size: 'mini',
            rounded: true
        });
        <?php
        Session::put("add_inci",false);
        Session::put("add_inci_message",false)
        ?>
        @endif

        @if(Session::get('edit_inci'))
        Lobibox.notify('success', {
            title: "",
            msg: "<?php echo Session::get("edit_inci_message"); ?>",
            size: 'mini',
            rounded: true
        });
        <?php
        Session::put("edit_inci",false);
        Session::put("edit_inci_message",false)
        ?>
        @endif

</script>
@endsection



