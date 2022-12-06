<?php
    use App\Section;
    use App\Release;
    use Illuminate\Support\Facades\Session;
    if(!Session::get('is_login')){
        \App\Http\Controllers\SystemController::logDefault('Logged In');
        Session::put('is_login',true);
    }
    $user = Session::get('auth');
    $code = 'temp;'.$user->section;
    $pending = \App\Tracking_Details::select(
        'date_in',
        'id',
        'route_no',
        'received_by',
        'code',
        'delivered_by',
        'action'
    )
        ->where('code',$code)
        ->where('status',0)
        ->count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>PIS</title>
    <meta name="description" content="3 styles with inline editable feature" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/jquery-ui.custom.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/jquery.gritter.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/bootstrap-editable.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/dropzone.min.css') }}" />

    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/fonts.googleapis.com.css') }}" />

    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />


    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/ace-rtl.min.css') }}" />

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="{{ asset('resources/assets_ace/js/ace-extra.min.js') }}"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <script src="{{ asset('resources/assets_ace/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('resources/assets_ace/js/raphael-min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('resources/plugin/Lobibox old/lobibox.css') }}" />

    <!--MORRIS -->
    <link rel="stylesheet" href="{{ asset('resources/plugin/morris/morris.css') }}">
    <script src="{{ asset('resources/plugin/morris/morris.min.js') }}"></script>
    <!--DATE RANGE-->
    <link href="{{ asset('resources/plugin/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <!-- RATING -->
    <link rel="stylesheet" href="{{ asset('resources/assets_ace/css/bootstrap-duallistbox.min.css') }}" />
    <script src="{{ asset('resources/assets_ace/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('resources/assets_ace/js/jquery.raty.min.js') }}"></script>
    <style>
        body {
            background: url('{{ asset('resources/img/backdrop.png') }}'), -webkit-gradient(radial, center center, 0, center center, 460, from(#ccc), to(#ddd));
        }
    </style>
</head>
<div class="loading"></div>

<body class="skin-2">

@if(isset(Request::segments()[0]) && Request::segments()[0] != 'new_employee')
    <nav class="navbar navbar-default navbar-static-top">
        <div style="background-color:#2F4054;padding:10px;font-weight: bold;">
            <div class="col-md-4">
                <span style="color: #f0ad4e;font-size: 12pt">Welcome</span> <span class="title-desc" style="color: white;font-size: 12pt">{{ Session::get('auth')->fname }} {{ Session::get('auth')->lname }}</span>
            </div>
            <div class="col-md-4">
                <span class="title-info" style="color: #f0ad4e;font-size: 12pt">Section:</span>
                <span class="title-desc" style="color: white;font-size: 12pt">
                <?php $section = Section::find(Session::get('auth')->section) ? Section::find(Session::get('auth')->section)->description: 'No Section'; ?>
                    {{ $section }}
                </span>
            </div>
            <div class="col-md-4">
                @if(Auth::check())
                    <span class="title-info" style="color: #f0ad4e;font-size: 12pt"><b>Date:</b></span> <span class="title-desc" style="color: white;font-size: 12pt">{{ date('M d, Y') }}</span>
                @endif
            </div>
            <div class="col-md-3">

            </div>
            <div class="clearfix"></div>
        </div>
        <div style="background-color:#00CC99;padding:10px;">
            <div class="container">
                <a href="{{ asset('/') }}">
                    <img src="{{ asset('resources/img/banner_dts2019.png') }}" class="img-responsive" />
                </a>
            </div>
        </div>

        <div style="background-color:#f8f8f8;border-color: #e7e7e7;">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    @include('layouts.navbar-nav')
                    <div class="navbar-buttons navbar-header pull-right" role="navigation">
                        <ul class="nav ace-nav">
                            <li class="grey dropdown-modal">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="ace-icon fa fa-tasks"></i>
                                    <span class="badge badge-grey">4</span>
                                </a>
                                <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                    <li class="dropdown-header">
                                        <i class="ace-icon fa fa-check"></i>
                                        4 Tasks to complete
                                    </li>
                                    Currently develop(Not yet)
                                    <!--<li class="dropdown-content">
                                        <ul class="dropdown-menu dropdown-navbar">
                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <span class="pull-left">Software Update</span>
                                                        <span class="pull-right">65%</span>
                                                    </div>

                                                    <div class="progress progress-mini">
                                                        <div style="width:65%" class="progress-bar"></div>
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <span class="pull-left">Hardware Upgrade</span>
                                                        <span class="pull-right">35%</span>
                                                    </div>

                                                    <div class="progress progress-mini">
                                                        <div style="width:35%" class="progress-bar progress-bar-danger"></div>
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <span class="pull-left">Unit Testing</span>
                                                        <span class="pull-right">15%</span>
                                                    </div>

                                                    <div class="progress progress-mini">
                                                        <div style="width:15%" class="progress-bar progress-bar-warning"></div>
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <span class="pull-left">Bug Fixes</span>
                                                        <span class="pull-right">90%</span>
                                                    </div>

                                                    <div class="progress progress-mini progress-striped active">
                                                        <div style="width:90%" class="progress-bar progress-bar-success"></div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>-->

                                    <li class="dropdown-footer">
                                        <a href="#">
                                            See tasks with details
                                            <i class="ace-icon fa fa-arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="purple dropdown-modal">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                                    <span class="badge badge-important">8</span>
                                </a>

                                <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                                    <li class="dropdown-header">
                                        <i class="ace-icon fa fa-exclamation-triangle"></i>
                                        8 Notifications
                                    </li>
                                    Currently develop(Not yet)
                                    <!--
                                    <li class="dropdown-content">
                                        <ul class="dropdown-menu dropdown-navbar navbar-pink">
                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                            <span class="pull-left">
                                                                <i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
                                                                New Comments
                                                            </span>
                                                        <span class="pull-right badge badge-info">+12</span>
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <i class="btn btn-xs btn-primary fa fa-user"></i>
                                                    Bob just signed up as an editor ...
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                            <span class="pull-left">
                                                                <i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
                                                                New Orders
                                                            </span>
                                                        <span class="pull-right badge badge-success">+8</span>
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                            <span class="pull-left">
                                                                <i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
                                                                Followers
                                                            </span>
                                                        <span class="pull-right badge badge-info">+11</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li> -->

                                    <li class="dropdown-footer">
                                        <a href="#">
                                            See all notifications
                                            <i class="ace-icon fa fa-arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="green dropdown-modal">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
                                    <span class="badge badge-success">5</span>
                                </a>

                                <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                    <li class="dropdown-header">
                                        <i class="ace-icon fa fa-envelope-o"></i>
                                        5 Messages
                                    </li>
                                    Currently develop(Not yet)
                                <!--
                            <li class="dropdown-content">
                                <ul class="dropdown-menu dropdown-navbar">
                                    <li>
                                        <a href="#" class="clearfix">
                                            <img src="" />
                                                <span class="msg-body">
                                                    <span class="msg-title">
                                                        <span class="blue">Alex:</span>
                                                        Ciao sociis natoque penatibus et auctor ...
                                                    </span>

                                                    <span class="msg-time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span>a moment ago</span>
                                                    </span>
                                                </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="clearfix">
                                            <img src="" class="msg-photo" alt="Susan's Avatar" />
                                                <span class="msg-body">
                                                    <span class="msg-title">
                                                        <span class="blue">Susan:</span>
                                                        Vestibulum id ligula porta felis euismod ...
                                                    </span>

                                                    <span class="msg-time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span>20 minutes ago</span>
                                                    </span>
                                                </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="clearfix">
                                            <img src="{{ asset('resources/assets_ace/images/avatars/avatar4.png') }}" class="msg-photo" alt="Bob's Avatar" />
                                                <span class="msg-body">
                                                    <span class="msg-title">
                                                        <span class="blue">Bob:</span>
                                                        Nullam quis risus eget urna mollis ornare ...
                                                    </span>

                                                    <span class="msg-time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span>3:15 pm</span>
                                                    </span>
                                                </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="clearfix">
                                            <img src="" class="msg-photo" alt="Kate's Avatar" />
                                                <span class="msg-body">
                                                    <span class="msg-title">
                                                        <span class="blue">Kate:</span>
                                                        Ciao sociis natoque eget urna mollis ornare ...
                                                    </span>

                                                    <span class="msg-time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span>1:33 pm</span>
                                                    </span>
                                                </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="clearfix">
                                            <img src="" class="msg-photo" alt="Fred's Avatar" />
                                                <span class="msg-body">
                                                    <span class="msg-title">
                                                        <span class="blue">Fred:</span>
                                                        Vestibulum id penatibus et auctor  ...
                                                    </span>

                                                    <span class="msg-time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span>10:09 am</span>
                                                    </span>
                                                </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            -->
                                    <li class="dropdown-footer">
                                        <a href="inbox.html">
                                            See all messages
                                            <i class="ace-icon fa fa-arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endif

<div class="container">
    <div class="space-6"></div>
    @yield('content')
    <div class="space-6"></div>
</div>

@if(isset(Request::segments()[0]))
    <div class="footer">
        <div class="footer-inner" >
            <div class="footer-content" style="background-color: #2F4054;color: white;padding: 10px;">
                <div class="container">
                    <p>Copyright &copy; 2017 DOH-RO7 All rights reserved</p>
                </div>
            </div>
        </div>
    </div>
    @include('modal')
@endif

<!-- basic scripts -->
<script>
    var loadingState = '<h1 class="header smaller lighter grey center"> <i class="ace-icon fa fa-spinner fa-spin orange bigger-300"></i></h1>';
    var loadingState1 = '<center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center>';
    //$(".pagination").addClass('pull-right');
</script>
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('resources/assets_ace/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
</script>

<script src="{{ asset('resources/assets_ace/js/bootstrap.min.js') }}"></script>
<!-- page specific plugin scripts -->
<script src="{{ asset('resources/assets_ace/js/jquery-ui.custom.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.gritter.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/bootbox.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.easypiechart.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.hotkeys.index.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/bootstrap-wysiwyg.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/select2.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/spinbox.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/bootstrap-editable.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/ace-editable.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery-ui.min.js') }}"></script>
<!-- page specific plugin scripts -->
<script src="{{ asset('resources/assets_ace/js/dropzone.min.js') }}"></script>

<!-- ace scripts -->
<script src="{{ asset('resources/assets_ace/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/ace.min.js') }}"></script>
<!-- PLUGIN JS -->
<script src="{{ asset('resources/plugin/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('resources/plugin/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('resources/plugin/Lobibox old/Lobibox.js') }}"></script>

<script src="{{ asset('resources/assets_ace/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/dataTables.buttons.min.js') }}"></script>


<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<script src="{{ asset('resources/assets_ace/js/wizard.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('resources/assets_ace/js/jquery-additional-methods.min.js') }}"></script>


@section('js')


@show

</body>
</html>
