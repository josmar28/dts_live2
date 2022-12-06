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
    <h2 class="page-header">Duration</h2>
    <form class="form-inline" method="POST" action="{{ asset('document/duration') }}" onsubmit="return searchDocument();" id="searchForm">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="{{ Session::get('keyword') }}" autofocus>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button

            <div class="btn-group">
            <a href="#duration_modal" data-toggle="modal" class="btn btn-info btn-sm btn-flat" onclick="durBody('empty')">
                            <i class="fa fa-hospital-o"></i> Add Document
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
                    <th width="30%">Section</th>
                    <th width="30%">Document Type</th>
                    <th width="20%">Duration</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td>
                    <a href="#duration_modal"
                      data-toggle="modal"
                      onclick="durBody('<?php echo $row->id ?>')">
                     {{$row->section}}
                     </a>
                   
                    </td>
                    <td>
                    {{$row->description}}
                    </td>
                    <td>
                    {{$row->duration}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $data->links() }}
@else
        <div class="alert alert-danger">
            <strong><i class="fa fa-times fa-lg"></i> No documents found! </strong>
        </div>
@endif
</div>

@endsection
@section('js')
<script>
  @if(Session::get('duration_delete'))
        Lobibox.notify('success', {
            title: "",
            msg: "Duration Succesfully Deleted",
            size: 'mini',
            rounded: true
        });
        <?php
        Session::put("duration_delete",false);
        ?>
        @endif


    function durBody(data){
            var json;
            if(data == 'empty'){
                json = {
                    "_token" : "<?php echo csrf_token()?>"
                };
            } else {
                json = {
                    "dur_id" : data,
                    "_token" : "<?php echo csrf_token()?>"
                };
            }
            console.log(data);
            var url = "<?php echo asset('document/duration_body') ?>";
            $.post(url,json,function(result){
                $(".duration_body").html(result);
            })
        }

    @if(Session::get('duration'))
        Lobibox.notify('success', {
            title: "",
            msg: "<?php echo Session::get("duration_message"); ?>",
            size: 'mini',
            rounded: true
        });
    <?php
        Session::put("duration",false);
        Session::put("duration_message",false)
    ?>
    @endif

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

    function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },2000);
    }
</script>
@endsection



