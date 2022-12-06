@extends('layouts.app')

@section('content')
    <span id="url" data-link="{{ asset('users') }}"></span>
    <span id="token" data-token="{{ csrf_token() }}"></span>
    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">System Users</h2>
        <form class="form-inline form-accept" action="{{ asset('/search/user') }}" method="GET">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Quick Search" autofocus>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                <div class="btn-group">
                    <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-link="{{ asset('user/new') }}" href="#new">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        @if(Session::has('used'))
            <div class="alert alert-danger">
                <strong>{{ Session::get('used') }}</strong>
            </div>
        @endif
        @if(count($users) > 0)
            <div class="table-responsive">
                <table class="table table-list table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name </th>
                        <th>Designation</th>
                        <th>Section / Division</th>
                        <th width="20%">Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <?php $section = \App\Section::where('id', $user->section)->pluck('description')->first(); ?>
                        <?php $division = \App\Division::where('id', $user->division)->pluck('description')->first(); ?>
                        <?php $designation = \App\Designation::where('id', $user->designation)->pluck('description')->first(); ?>

                        <tr>
                            <td><a href="#user" data-id="{{ $user->id }}" data-link="{{ asset('user/edit') }}" class="title-info">{{ $user->username }}</a></td>
                            <td><a href="#user" data-id="{{ $user->id }}" data-link="{{ asset('user/edit') }}" class="text-bold">{{ $user->fname ." ". $user->mname." ".$user->lname }}</a></td>
                            <td>{{ $designation }}</td>
                            <td>
                                {{ $section }}<br>
                                <em>({{ $division }})</em>
                            </td>
                            <td>
                                <div class="btn-group">
                                    
                                    <a href="#user" class="btn btn-sm btn-info" data-toggle="modal" data-link="{{ asset('user/edit') }}" data-id="{{ $user->id }}">
                                        <i class="fa fa-pencil"></i>  Update
                                    </a>
                                </div>
                                <button type="button" data-id="{{ $user->id }}" data-link="{{ asset('user/remove') }}" class="btn btn-danger" id="delete_user" onclick="del_user(this);" name="delete" value="delete" ><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        @else
            <div class="alert alert-danger">
                <strong><i class="fa fa-times fa-lg"></i>No users found.</strong>
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
    @parent
    <script>
        (function($){
            $('.form-accept').submit(function(event){
                $(this).submit();
            });
        })($);

    </script>
@endsection



