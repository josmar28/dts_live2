@extends('layouts.app')

@section('content')
    <span id="url" data-link="{{ asset('/designation') }}"></span>
    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Designations</h2>
        <form class="form-inline form-accept" action="{{ asset('search/designation') }}" id="search_designation" method="GET">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Quick Search" autofocus>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                <div class="btn-group">
                    <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-link="{{ asset('/designation/create') }}" href="#new">
                        <i class="fa fa-plus"></i>  Add New
                    </a>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        @if(count($designations) > 0)
            <div class="table-responsive">
                <table class="table table-list table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th width="20%">Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($designations as $d)
                        <tr>
                            <td class="title-info">{{ $d->description }}</td>
                            <td> 
                            <a href="#designation_newedit" class="btn btn-sm btn-info designation_newedit" data-link="{{ asset('/edit/designation') }}"  data-toggle="modal"  data-id="{{ $d->id }}">
                                        <i class="fa fa-pencil"></i>  Update
                                    </a>
                            <a href="" class="btn btn-sm btn-danger delete_designation" data-link="{{ asset('/remove/designation') }}"  data-toggle="modal"  data-id="{{ $d->id }}">
                                        <i class="fa fa-pencil"></i>  Delete
                                    </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $designations->links() }}
        @else
            <div class="alert alert-danger">
                <strong><i class="fa fa-times fa-lg"></i>Record is empty.</strong>
            </div>
        @endif
    </div>
    <span data-link="{{ asset('/remove/designation') }}" id="delete"></span>
    <span data-link="{{ asset('/edit/designation') }}" id="edit"></span>
    <span id="token" data-token="{{ csrf_token() }}"></span>
@endsection

@section('js')
    <script> 
$('.delete_designation').on('click', function(e){
    var url = $(this).data('link');
    var data = {
        "id" : $(this).data('id'),
       "_token" : "<?php echo csrf_token(); ?>"
   };
   $('#confirmation').modal('show');
   $('#confirm').click(function(){
            $.post(url,data,function(response){
            if(response == 'true')
            {
                window.location.reload();
                 
             }else{
                 alert('error');
             }
            });
         });
    });

    
    @if(Session::get('designation_update'))
            Lobibox.notify('success', {
            title: "",
            msg: "Designation Successfully Updated",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("designation_update",false);
        ?>
    @endif

    @if(Session::get('designation_create'))
            Lobibox.notify('success', {
            title: "",
            msg: "Designation Successfully Created",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("designation_create",false);
        ?>
    @endif

    @if(Session::get('designation'))
                    Lobibox.notify('warning', {
                        title: "",
                        msg: "Designation Deleted",
                        size: 'mini',
                        rounded: true
                    });
                <?php
                    Session::put("designation",false);
                ?>
    @endif

    $('.designation_newedit').on('click', function(e){
            var url = $(this).data('link');
            var data = {
                "id" : $(this).data('id')
            };
            $.get(url,data,function(response){
                $('.designation_body').html(response);
            });
    });
    
        $('#search_designation').submit(function(){
           $(this).submit();
        });
    </script>
@endsection