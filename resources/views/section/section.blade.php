<?php use \App\Http\Controllers\SectionController as Section; ?>
@extends('layouts.app')
@section('content')
    <span id="url" data-link="{{ asset('searchSection') }}"></span>
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
        <h2 class="page-header">Sections</h2>
        <form class="form-inline form-accept" action="{{ asset('searchSection') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" class="form-control" value="{{ Session::get("search") }}" placeholder="Quick Search" name="search" autofocus>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                <div class="btn-group">
                    <a href="#document_form" class="btn btn-success" data-toggle="modal" data-link="{{ asset('addSection') }}">
                        <i class="fa fa-plus"></i>  Add New
                    </a>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        @if(count($section))
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
                    @foreach($section as $sec)
                        <tr>
                            <td><a class="title-info">{{ $sec->description }}</a></td>
                            <td>{{ Section::getHead($sec->head) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#document_form" class="btn btn-sm btn-info" data-toggle="modal" data-link="{{ asset('updateSection/'.$sec->id.'/'.$sec->division.'/'.$sec->head.'/') }}">
                                        <i class="fa fa-pencil"></i>  Update
                                    </a>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" value="{{ $sec->description }}" data-link="{{ asset('deleteSection/'.$sec->id) }}" id="deleteValue" data-toggle="modal" data-target="#confirmation" onclick="deleteSection($(this));"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $section->links() }}
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
    @if(Session::get('section_save'))
            Lobibox.notify('success', {
            title: "",
            msg: "Section Successfully Created",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("section_save",false);
        ?>
    @endif

    @if(Session::get('section_delete'))
                    Lobibox.notify('warning', {
                        title: "",
                        msg: "Section Deleted",
                        size: 'mini',
                        rounded: true
                    });
                <?php
                    Session::put("section_delete",false);
                ?>
    @endif

@if(Session::get('section_update'))
                    Lobibox.notify('success', {
                        title: "",
                        msg: "Section Successfully Updated",
                        size: 'mini',
                        rounded: true
                    });
                <?php
                    Session::put("section_update",false);
                ?>
    @endif
</script> 
@endsection


