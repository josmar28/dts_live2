@extends('layouts.app')

@section('content')
    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Service Types</h2>
        <a href="#add-service-modal" data-toggle="modal" class="btn btn-info btn-sm btn-flat">
                            <i class="fa fa-hospital-o"></i> Add Service
                        </a>
        <div class="page-divider"></div>
            <div class="table-responsive">
                <input type="hidden" id="token" value="{{ csrf_token() }}">
                <table class="table table-bordered table-type  table-hover table-striped" style="text-align:center">
                <thead>
                    <tr >
                        <th>Service Type</th>
                        <th>Timeframe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data))
                        @foreach($data as $row)
                            <tr>
                            
                                <td>
                                    {{ $row->description}}
                                </td>
                                <td>
                                   @if($row->days) {{ $row->days }} Day/s @endif @if($row->hours) {{ $row->hours }} Hour/s @endif @if($row->minutes) {{ $row->minutes }} Minute/s @endif
                                </td>
                                <td>
                                    <a href="#add-service-modal" data-id="{{$row->id}}" data-toggle="modal" class="btn btn-info btn-sm btn-flat btn_view">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="#delete-service-type-modal" data-id="{{$row->id}}" data-toggle="modal" class="btn btn-danger btn-sm btn-flat btn_delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>
                                
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            </div>
    </div>
@include('services.modal.service')
@endsection
@section('js')
@include('services.scripts.service')
@endsection
@section('plugin')
    <script src="{{ asset('resources/plugin/iCheck/icheck.min.js') }}"></script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('resources/plugin/iCheck/all.css') }}">
    <style>
        .table-responsive>.fixed-column {
            position: absolute;
            width: auto;
            border-right: 1px solid #ddd;
            background-color: #ddd;
            z-index:3000;
        }
        .table-type th {
            text-transform: uppercase;
            text-align: center;
        }
        .table tr td {
            text-align: center;
        }
    </style>
@endsection

