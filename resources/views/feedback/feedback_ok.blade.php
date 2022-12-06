<?php
use Illuminate\Support\Facades\Session;

?>


@extends('layouts.app')

@section('content')
    <div class="col-md-9 wrapper">
        <div class="alert alert-jim">
            <h3 class="page-header text-success"><i class="fa fa-check"></i> Sent
            </h3>
            <div class="alert alert-success text-center">
                Thank you for getting in touch!<br>
                We appreciate you contacting us about the DTS. We try to respond as soon as possible. We will get back to you within a few hours.<br>
                Have a great day ahead {{ (Session::has('name') ? Session::get('name') : "" ) }}!
            </div>
        </div>
    </div>
@include('sidebar')
@endsection


