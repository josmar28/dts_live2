<?php
use App\Http\Controllers\SectionController as Sec;
use App\Http\Controllers\AdminController as Admin;
?>
@extends('layouts.app')

@section('content')

    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Reported Documents - {{ $year }}</h2>
        <!-- Nav tabs -->
        <?php
            $monthArray = ['02' => "February",'03' => "March",'04' => "April",'05' => "May",'06' => "June",'07' => "July",'08' => "August",'09' => "September",'10' => "October",'11' => "November",'12' => "December"];
            $thHeadColor = ['text-success','text-info','text-warning','text-danger'];
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#January" role="tab" aria-controls="home" aria-selected="true">January</a>
            </li>
            @foreach($monthArray as $key => $month)
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#{{ $month }}" role="tab" aria-controls="profile" aria-selected="false">{{ $month }}</a>
            </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="January" role="tabpanel" aria-labelledby="home-tab">
                {{--<div class="clearfix"></div>
                <div class="page-divider"></div>--}}
                <?php $count = 0; ?>
                @foreach(\App\Division::get() as $division)
                    <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                        <thead>
                        <tr>
                            <th colspan="2" class="bg-{{ explode('-',$thHeadColor[$count])[1] }} text-bold {{ $thHeadColor[$count] }} text-uppercase" style="padding: 15px 10px;">{{ $division->description }} - January</th>
                        </tr>
                        <tr>
                            <th class="col-sm-6">Sections</th>
                            <th class="col-sm-6">Count of reported documents</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Section::where('division','=',$division->id)->get() as $section)
                        <tr>
                            <td>{{ $section->description }}</td>
                            <td>{!! isset($reportedDocument[$section->id.'-01']) ? nl2br("<strong style='color:#f06e20;'>".$reportedDocument[$section->id.'-01']."</strong>") : 0 !!}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <?php $count++; ?>
                @endforeach
            </div>
            @foreach($monthArray as $key => $month)
                <div class="tab-pane fade" id="{{ $month }}" role="tabpanel" aria-labelledby="profile-tab">
                    <?php $count = 0; ?>
                    @foreach(\App\Division::get() as $division)
                    <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                        <thead>
                        <tr>
                            <th colspan="3" class="bg-{{ explode('-',$thHeadColor[$count])[1] }} text-bold {{ $thHeadColor[$count] }} text-uppercase" style="padding: 15px 10px;">{{ $division->description.' - '.$month }}</th>
                        </tr>
                        <tr>
                            <th class="col-sm-6">Sections</th>
                            <th class="col-sm-6">Count of reported documents</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Section::where('division','=',$division->id)->get() as $section)
                        <tr>
                            <td >{{ $section->description }}</td>
                            <td >{!! isset($reportedDocument[$section->id.'-'.$key]) ? nl2br("<strong style='color:#f06e20;'>".$reportedDocument[$section->id.'-'.$key]."</strong>") : 0  !!}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <?php $count++; ?>
                    @endforeach
                </div>
            @endforeach
        </div>

    </div>

@endsection
@section('plugin')

@endsection

@section('css')

@endsection

