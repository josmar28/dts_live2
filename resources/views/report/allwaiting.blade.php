<?php
    use App\Http\Controllers\SectionController as Sec;
    use App\Http\Controllers\AdminController as Admin;
    use App\User;
    $year = Session::get('report_year');
    $month = Session::get('report_month');
    $on_keyword = Session::get('on_keyword');
?>
@extends('layouts.app')
<style>
            .table-fixed{
            width: 100%;
            background-color: #f3f3f3;
            tbody{
                height:200px;
                overflow-y:auto;
                width: 100%;
                }
            thead,tbody,tr,td,th{
                display:block;
            }
            tbody{
                td{
                float:left;
                }
            }
            thead {
                tr{
                th{
                    float:left;
                background-color: #f39c12;
                border-color:#e67e22;
                }
                }
            }
            }
                </style>
<style>

@page {
    size: auto;
    margin: 5;
}

    @media print {
      
  #printPageButton {
    display: none;
  }
  .printhide {
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
        <h2 class="page-header">Waiting Documents</h2>
        <form class="form-inline" method="POST" action="{{ asset('documents/report/ongoingsearch') }}" onsubmit="return searchDocument()">
            {{ csrf_field() }}
            <div class ="printhide">
               <div class="row">
                    <div class="col-md-6">
                        <!-- <input type="text" class="form-control" placeholder="Quick Search" name="keyword" autofocus> -->
 
                        <!-- <button type="submit" class="btn btn-info form-control" ><i class="fa fa-search"></i> Search</button> -->
                       <!-- @if(isset($on_keyword) > 0)
                        <a href="{{ asset('documents/report/ongoinghome/'.$id)}}" class="btn btn-info form-control">
                        View All</a>
                        @endif -->
                        
                        <input type="button" class="btn btn-warning form-control" onclick="printDiv('printableArea')" value="Print" />
                    </div>
                 </div>
            </div>
</br>
            <div id="scrollid" style="height: 600px;overflow: scroll;">
            <div id="printableArea">
                <table class="table table-striped table-hover table-fixed" >
                 <thead>
                    <tr>
                        <th class="col-md-1">Route #</th>
                        <th class="col-md-3"> </th>
                        <th class="col-md-3">Released date</th>
                        <th class="col-md-2">Released by</th>
                        <th class="col-md-3">Released section to</th>
                        <th class="col-md-3">Description</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $on)
                    <?php
                      $user = User::find($on->released_by);
                      $section = App\Section::find($on->id);
                   ?>
                    <tr>
                        <td><a href="#track" data-link="{{ asset('document/track/'.$on->route_no) }}" data-route="{{ $on->route_no }}" data-toggle="modal" class="btn btn-sm btn-info">Track</a></td>     
                        <td>{{$on->route_no}}</td>
                        <td>{{$on->released_date}}</td>  
                        <td>{{$user->fname}} {{$user->lname}}</td>  
                        <td>{{$section->description}}</td>  
                        <td>{{$on->remarks}}</td>             
                    </tr>
                
                    @endforeach
                </tbody>
             </table>
            </div>
                </div>
    </div>
        </form>
        <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
       </div> -->
@endsection

<script>

$("a[href='#track']").on('click',function(){
        $('.track_history').html(loadingState);
        var route_no = $(this).data('route');
        var url = $(this).data('link');
        $('#track_route_no').val('Loading...');
        setTimeout(function(){
            $('#track_route_no').val(route_no);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    console.log(url);
                    $('.track_history').html(data);
                }
            });
        },1000);
        
    });

    function ongoinghome(sec_id){
        console.log(sec_id);
        var url = "<?php echo asset('documents/report/ongoinghome'); ?>";
        var json = {
            "sec_id" : sec_id,
            "_token" : "<?php echo csrf_token(); ?>"
        };
        $.post(url,json,function(result){
            $(".ongoing_body").html(result);
        });
    }
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
@section('plugin')

@endsection

@section('css')

@endsection

