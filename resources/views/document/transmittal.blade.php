<?php
    use App\Tracking_Details;
    use App\Http\Controllers\DocumentController as Doc;
    
    $user = Session::get('auth');
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
    <h2 class="page-header">Transmittals</h2>
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <input type="hidden" id="url" value="{{ asset('document/transmittal/delete') }}">
    <form class="form-inline" method="POST" action="{{ asset('document/transmittal') }}" onsubmit="return searchDocument();" id="searchForm">
        {{ csrf_field() }}
    
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="@if(isset($keyword)){{$keyword}}@endif"autofocus>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
           
            <div class="btn-group">

                <a href="#transmittal_modal" data-toggle="modal" class="btn btn-success dropdown-toggle trans_btn" onclick="documentRoute()">
                <i class="fa fa-plus"></i>  Create Transmittal
                </a>
           
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
    <div class="page-divider"></div>
    @if(count($data))
    <div class="table-responsive">
        <table class="table table-list table-hover table-striped">
            <thead>
                <tr>
                    <th width="10%"></th>
                    <th width="15%">TRN</th>
                    <th width="15%">Description</th>
                    <th width="15%">Encoded By</th>
                    <th width="10%">Created At</th>
                    <th width="3%">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $doc)
            @if($doc->status == 'draft')
                <tr>
                    <td>
                        <a href="{{ asset('document/print/transmittal/'.$doc->trn) }}" target="_blank" data-trn = "{{$doc->trn}}" class="btn btn-success btn-xs">
                        <i class="fa fa-print"></i> Print
                        </a> 
                        <a href="#transmittal_delete" data-trn="{{$doc->trn}}" data-dismiss="modal" data-toggle="modal" class="btn btn-danger btn-xs wholetrans_delete">
                        <i class="fa fa-trash"></i> Remove
                    </a>
                    </td>
                   <td> <a class="title-info" target="_blank" href="{{ asset('document/pertransmittal/'.$doc->trn.'/') }}">{{ $doc->trn }}</a></td>
                   <td> {{ $doc->description }} </td>
                   <td>{{ $doc->fname }} {{ $doc->lname }}</td>
                   <td>{{ $doc->created_at }} </td>
                   <td> <a data-trn = "{{$doc->trn}}" data-desc = "{{ $doc->description }}" class="btn btn-success btn-xs btn_released">
                        <i class="fa fa-check"></i> Released
                        </a> </td>
                </tr>
            @elseif($doc->status == 'completed')
            <tr>
                <td>
                        <a href="{{ asset('document/print/transmittal/'.$doc->trn) }}" target="_blank" data-trn = "{{$doc->trn}}" class="btn btn-success btn-xs">
                        <i class="fa fa-print"></i> Print
                        </a> 
                       
                    </td>
                   <td> <a class="title-info" target="_blank" href="{{ asset('document/pertransmittal/'.$doc->trn.'/') }}">{{ $doc->trn }}</a></td>
                   <td> {{ $doc->description }} </td>
                   <td>{{ $doc->fname }} {{ $doc->lname }}</td>
                   <td>{{ $doc->created_at }} </td>
                   <td> </td>
                </tr>
            @endif
             @endforeach
            </tbody>
            {{ $data->links() }}
            <div id="myDIV" style="display:none">  
            <a href="#transmittal_modal" data-toggle="modal" class="btn btn-success dropdown-toggle" >Add transmittal</a>
           </div>
        </table>
        </form>
        </div>
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

@section('js')
<script>

    $('.btn_released').click(function (){
        var trn = $(this).data('trn');
        var description = $(this).data('desc');
         var data = {
                'trn':$(this).data('trn'),
                '_token':$("#token").val()
            };
            var url = "<?php echo url('document/transmittal/check');?>";
            $.ajax({
                url: url,
                type: 'POST',
                 data : data,
             success: function(data){
                var url1 = "<?php echo url('document/transmittal/perdoc/update');?>";
                var data1 = [];
                  $.each( data, function( key, value ) {
                    data1.push(value.route_no)
                    });
                        $.ajax({
                                url: url1,
                                type: 'POST',
                                data : {
                                    'route_no' : data1,
                                    '_token':$("#token").val()
                                    },
                            success: function(result){
                                var url2 = "<?php echo url('document/transmittal/perdoc/check');?>";
                                var data2 = {
                                        'route_no' : data1,
                                        '_token':$("#token").val()
                                    };
                                        $.ajax({
                                            url: url2,
                                            type: 'POST',
                                            data : data2,
                                            success: function(results){
                                                var data4 = {
                                                    'trn':trn,
                                                    '_token':$("#token").val()
                                                };
                                        var url4 = "<?php echo url('document/transmittal/perdoc/update/status');?>";
                                            $.ajax({
                                                    url: url4,
                                                    type: 'POST',
                                                    data : data4,
                                                    success: function(resultss) {
                                                data = results;
                                                if(results == 0)
                                                {
                                                    @if(Session::get('nodoclapsed'))
                                                        Lobibox.notify('success', {
                                                            msg: 'No Document Reported, Transmittal Updated'
                                                        });
                                                        <?php Session::forget('nodoclapsed'); ?>
                                                    @endif
                                                }
                                                else{
                                        <?php echo 'var url3 ="'.asset('chd12report/transmittal/incident').'";';?>
                                            $('#transincident').data('route',trn).modal('show');
                                            setTimeout(function(){
                                                $('#trn').val(trn);
                                                $('#trn2').val(trn);
                                                $('#data').val(data);
                                                $.ajax({
                                                    url: url3,
                                                    type: 'GET',
                                                    success: function(data) {
                                                            $('textarea#inci_subject').val(description);
                                                            $('.transinci').html(data);
                                                        }
                                                            });
                                                        },1000);
                                                            }

                                                    }
                                              });


                                            }
                                        });
                                  }
                         });

                  }
            });
        
    });

    @if(Session::get('transincdi_add'))
      Lobibox.notify('success', {
         msg: 'Incident log Inserted and Transmittal Updated'
             });
         <?php Session::forget('transincdi_add'); ?>
    @endif

     $('.wholetrans_delete').click(function(){
            var trn_del = $(this).data('trn')

            $(".trn_delete").val(trn_del);
        });
       
       $('.transmittal_delete').click(function(){
        if($(this).prop('checked') == false){
            var data = {
                'id':$(this).data('id'),
                'trn':$(this).data('trn'),
                '_token':$("#token").val()
            };
            }
            var url = $('#url').val();
            $('.loading').show();
                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function(data) {
                        console.log(data);
                        $('.loading').hide();
                        location.reload();
                    }
                });
        });

$('.trans_btn').click(function(){
    $('.loading').show();
    var url = "<?php echo asset('document/transmittal_body'); ?>";
        var json = {
            "_token" : "<?php echo csrf_token(); ?>",
            "valid" : 'create'
        };
        $.post(url,json,function(result){
            $('.loading').hide();
            $(".transmittal_body").html(result);
        });

});
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

    @if(Session::get('wholetrans_delete'))
        Lobibox.notify('success', {
            msg: 'Transmittal Deleted'
        });
        <?php Session::forget('wholetrans_delete'); ?>
    @endif
        
    @if(Session::get('trans_delete'))
        Lobibox.notify('success', {
            msg: 'Transmittal Deleted'
        });
        <?php Session::forget('trans_delete'); ?>
    @endif

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



