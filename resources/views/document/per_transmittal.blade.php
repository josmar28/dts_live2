<?php
    use App\Tracking_Details;
    use App\Http\Controllers\DocumentController as Doc;
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
    <h2 class="page-header">Transmittal Documents</h2>
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <input type="hidden" id="url" value="{{ asset('document/transmittal/delete') }}">
    <form class="form-inline" method="POST" action="{{ asset('document/pertransmittal/'.$TRN.'/'.$desc) }}" onsubmit="return searchDocument();" id="searchForm">
        {{ csrf_field() }}
        <input type="hidden" name="trn" value="{{$TRN}}">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="" autofocus>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
        @if($status == 'draft')
            <div class="btn-group">
                <a href="#transmittal_modal" data-desc ="{{$desc}}" data-trn ="{{$TRN}}" data-toggle="modal" class="btn btn-success dropdown-toggle trans_btn">
                <i class="fa fa-plus"></i>  Add Document
                </a>
            </div>
            @endif
            
        </div>
        <center><h1>{{$TRN}}</h1></center>
    </form>
    <div class="clearfix"></div>
    <div class="page-divider"></div>
    @if(count($data))
    <div class="table-responsive">
        <table class="table table-list table-hover table-striped">
            <thead>
                <tr>
                    <th width="8%"></th>
                    <th width="15%">Route No</th>
                    <th width="15%">Document Type</th>
                    <th width="15%">Encoded By</th>
                    <th width="20%">Created At</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $doc)
                <tr>
                @if($doc->status == 'draft')
                <td> 
                    <input type="checkbox" data-trn = "{{$TRN}}" data-id ="{{ $doc->transdata_id }}" name="route_no[]" class="transmittal_delete" value="{{ $doc->route_no }}" checked>
                <a href="#track" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-route="{{ $doc->route_no }}" data-toggle="modal" class="btn btn-sm btn-success">Track</a>
                  </td>
                  @else
                  <td>
                <a href="#track" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-route="{{ $doc->route_no }}" data-toggle="modal" class="btn btn-sm btn-success">Track</a>
                  </td>
                  @endif
                   <td>{{ $doc->route_no }}</td>
                   <td>{{ $doc->doc_type }}</td>
                   <td>{{ $doc->fname }} {{ $doc->lname }}</td>
                   <td>{{ $doc->created_at }} </td>
                </tr>
             @endforeach
            </tbody>
            {{ $data->links() }}
          
        </table>
        </form>
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
@section('plugin_old')

@section('js')
<script>
       
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
    var url = "<?php echo asset('document/transmittal_body'); ?>";
        var json = {
            "_token" : "<?php echo csrf_token(); ?>",
            "valid" : 'add',
            "act_trn" :$(this).data('trn'),
            "desc" : $(this).data('desc')
        };
        $.post(url,json,function(result){
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

    @if(Session::get('return_del'))
        Lobibox.notify('success', {
            msg: 'Transmittal cannot be empty'
        });
        <?php Session::forget('return_del'); ?>
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



