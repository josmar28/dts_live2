<?php
    use App\Tracking_Details;
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
    <h2 class="page-header">Documents</h2>

    
    <form class="form-inline" method="POST" action="{{ asset('document') }}" onsubmit="return searchDocument();" id="searchForm">
        {{ csrf_field() }}
       

        <div class="form-group">
            <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="{{ Session::get('keyword') }}" autofocus>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>

           
            <div class="btn-group">
                <a href="#all_form" data-toggle="modal" class="btn btn-success dropdown-toggle" onclick="documentRoute()">
                <i class="fa fa-plus"></i>  Add New
                        </a>
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
    <div class="page-divider"></div>
    @if(count($documents))
    <div class="table-responsive">
        <table class="table table-list table-hover table-striped">
            <thead>
                <tr>
                    <th width="8%"></th>
                    <th width="20%">Route #</th>
                    <th width="15%">Prepared Date</th>
                    <th width="20%">Document Type</th>
                    <th>Remarks / Additional Information</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $key => $doc)
                <tr>
                    <td class="action">
                        <a href="#track" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-route="{{ $doc->route_no }}" data-toggle="modal"  class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a>
                        <br />
                        <?php
                            $routed = \App\Tracking_Details::where('route_no',$doc->route_no)
                                ->count();
                        ?>
                        @if($routed < 2)
                            <?php
                               $doc_id = Tracking_Details::where('route_no',$doc->route_no)
                               ->orderBy('id','desc')
                               ->pluck('id')
                               ->first();
                            ?>
                            <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $doc_id }}" data-route_no="{{ $doc->route_no }}" onclick="putRoute($(this))" type="button" class="btn btn-info btn-sm">Release To</button>
                        @endif
                        <br />
                        <form target="_blank" class="form-inline" method="POST" action="{{ asset('pdf/chdprint') }}">
                        {{ csrf_field() }}
                            <!-- <input type="checkbox" id="<?php echo "checked".$key;?>" name="route_no[]" value="{{ $doc->route_no }}" onclick="handleClick()"> -->
                    </td>
                    <td>
                
                    <a class="title-info" data-route="{{ $doc->route_no }}" data-backdrop="static" data-link="{{ asset('/document/info/'.$doc->route_no.'/'.$doc->doc_type) }}" href="#document_form" data-toggle="modal">{{ $doc->route_no }}</a>
                  
                    </td>
                    <td>{{ date('M d, Y',strtotime($doc->prepared_date)) }}<br>{{ date('h:i:s A',strtotime($doc->prepared_date)) }}</td>
                    <td>{{ \App\Http\Controllers\DocumentController::getDocDesc2($doc->doc_type)}}</td>
                    <td>
                      
                            {!! nl2br($doc->description) !!}
                      
                    </td>
                </tr>
                @endforeach
            </tbody>
            <div id="myDIV" style="display:none">  
            <a href="#transmittal_modal" data-toggle="modal" class="btn btn-success dropdown-toggle" >Add transmittal</a>
           </div>
        </table>
        
        </form>
    </div>
    {{ $documents->links() }}
    @else
        <div class="alert alert-danger">
            <strong><i class="fa fa-times fa-lg"></i> No documents found! </strong>
        </div>
    @endif
</div>
@include('modal.pendinginci')
@include('modal.release_modal')
@include('modal.prr_supply_modal')

@endsection
@section('plugin_old')

@include('js.release_js')
<script>
       
        function handleClick(){
            var count = 0;
       
            var input= document.getElementsByName("route_no[]");
       
            for(var i = 0;i<input.length; i++ )
            {
                var id = "checked"+i;
                var id2 = document.getElementById(id);

                if(id2.checked == true)
                {
                    count++;
                }
            
            }
            var x = document.getElementById("myDIV");
            if (count > 0 ) {
                x.style.display = "block";
              
            } else {
                x.style.display = "none";
                
            }

           
           
        }


    @if(Session::get('add_trans'))
        Lobibox.notify('success', {
            msg: 'Successfully Added Transmittal'
        });
        <?php Session::forget('add_trans'); ?>
    @endif

    @if(Session::get('updated'))
        Lobibox.notify('success', {
            msg: 'Successfully Updated!'
        });
        <?php Session::forget('updated'); ?>
    @endif
    @if(Session::get('updated'))
        Lobibox.notify('success', {
            msg: 'Successfully Updated!'
        });
        <?php Session::forget('updated'); ?>
    @endif
    @if(Session::get('added'))
        Lobibox.notify('success', {
            msg: 'Successfully Added!'
        });
        <?php Session::forget('added'); ?>
    @endif
    @if(Session::get('deleted'))
        Lobibox.notify('warning', {
            msg: 'Successfully Deleted!'
        });
        <?php Session::forget('deleted'); ?>
    @endif
    @if(Session::get('deletedPR'))
        Lobibox.notify('warning', {
            msg: 'Successfully PR Deleted!'
        });
        <?php Session::forget('deletedPR'); ?>
    @endif
        @if (session('status'))
            <?php
                $status = session('status');
            ?>
            @if($status=='releaseAdded')
            Lobibox.notify('success', {
                msg: 'Successfully Released!'
            });
        @endif
    @endif

    $('a[href="#allform"]').on('click',function(){
        var title = $(this).html();
        var type = 'TEV';
        <?php echo 'var url ="'.asset('document/create').'";';?>
        $('#general_form_title').html(title);
        $.ajax({    
            url:url+'/'+type,
            type: 'GET',
            success: function(data){
                $('.allform').html(data);
            }
        })
    });

    function documentRoute(){
        var url = "<?php echo asset('document/routing'); ?>";
        var json = {
            "doc_type" : " ",
            "_token" : "<?php echo csrf_token(); ?>"
        };
        $.post(url,json,function(result){
            $(".allform").html(result);
        });
    }

    $("a[href='#prr_supply_modal']").on('click',function(){
        var route_no = $(this).data('route');
        $('.modal-title').html(route_no);
        $('.modal_content').html(loadingState);
        var url = $(this).data('link');
        setTimeout(function(){
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.modal_content').html(data);
                    var datePicker = $('body').find('.datepicker');
                    $('input').attr('autocomplete', 'off');
                }
            });
        },1000);
    });

    $('a[href="#general_form"]').on('click',function(){
        var title = $(this).html();
        var type = $(this).data('type');
        <?php echo 'var url ="'.asset('document/create/').'";';?>
        $('#general_form_title').html(title);
        $.ajax({
            url:url+'/'+type,
            type: 'GET',
            success: function(data){
                $('#general_form_content').html(data);
            }
        })
    });
    
function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },2000);
    }
</script>
@endsection



