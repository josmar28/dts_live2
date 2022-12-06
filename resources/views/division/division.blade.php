<?php use \App\Http\Controllers\DivisionController as Division; ?>
@extends('layouts.app')
@section('content')
    <span id="url" data-link="{{ asset('searchDivision') }}"></span>
    <span id="token" data-token="{{ csrf_token() }}"></span>
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
        <h2 class="page-header">Divisions</h2>
        <form class="form-inline form-accept">
            <div class="form-group">
                <input type="text" class="form-control" id="search" value="{{ Session::get('search') }}" placeholder="Quick Search" autofocus>
                <button type="button" class="btn btn-default" onclick="searchDivision($(this));" data-link="{{ asset('searchDivision') }}"><i class="fa fa-search"></i> Search</button>
                <div class="btn-group">
                    <a href="#document_form" class="btn btn-success" data-toggle="modal" data-link="{{ asset('addDivision') }}">
                        <i class="fa fa-plus"></i>  Add New
                    </a>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        @if(count($division))
            <div class="table-responsive">
                <table class="table table-list table-hover table-striped">
                    <thead>
                    <tr>
                        <th width="40%">Description</th>
                        <th width="40%">Head</th>
                        <th>Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($division as $div)
                        <tr>
                            <td><a class="title-info">{{ $div->description }}</a></td>
                            <td>{{ Division::getHead($div->head) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#document_form" class="btn btn-sm btn-info" data-toggle="modal" data-link="{{ asset('updateDivision/'.$div->id.'/'.$div->head) }}">
                                        <i class="fa fa-pencil"></i>  Update
                                    </a>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" value="{{ $div->description }}" data-link="{{ asset('deleteDivision/'.$div->id) }}" id="deleteValue" data-toggle="modal" data-target="#confirmation" onclick="deleteDivision($(this));"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $division->links() }}
        @else
            <div class="alert alert-danger">
                <strong><i class="fa fa-times fa-lg"></i> No documents found! </strong>
            </div>
        @endif
    </div>
@endsection
@section('plugin')
    <script src="{{ asset('resources/plugin/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('resources/plugin/daterangepicker/daterangepicker.js') }}"></script>
@endsection


@section('css')
    <link href="{{ asset('resources/plugin/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script> 
    @if(Session::get('division_save'))
            Lobibox.notify('success', {
            title: "",
            msg: "Division Successfully Created",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("division_save",false);
        ?>
    @endif

    @if(Session::get('division_delete'))
                    Lobibox.notify('warning', {
                        title: "",
                        msg: "Division Deleted",
                        size: 'mini',
                        rounded: true
                    });
                <?php
                    Session::put("division_delete",false);
                ?>
    @endif

@if(Session::get('updivision_save'))
                    Lobibox.notify('success', {
                        title: "",
                        msg: "Division Successfully Updated",
                        size: 'mini',
                        rounded: true
                    });
                <?php
                    Session::put("updivision_save",false);
                ?>
    @endif
</script> 
@endsection

