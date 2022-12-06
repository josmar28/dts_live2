<?php
    use App\Http\Controllers\DocumentController as Doc;
    use App\User;
    use App\Http\Controllers\ReleaseController as Rel;
?>
@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="<?php echo asset('resources/plugin/dataTable/css/dataTables.bootstrap.min.css');?>" type="text/css" />
    <style>
        .action .btn {
            width:100%;
            margin-bottom: 5px;
        }
    </style>
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">Pending Documents</h2>

            <form id="accept_form" method="post">
                {{ csrf_field() }}
                @if (session('status'))
                    <?php
                    $status = session('status');
                    ?>
                    @if(isset($status['success']))
                        <div class="alert alert-success">
                            <ul>
                                @foreach ($status['success'] as $success)
                                    <li>{!! $success !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(isset($status['errors']))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($status['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if($status=='documentAccepted')
                        <div class="alert alert-success">
                            <i class="fa fa-check"></i> Successfully accepted!
                        </div>
                    @endif
                    @if($status=='releaseAdded')
                        <div class="alert alert-success">
                            <i class="fa fa-check"></i> Successfully released!
                        </div>
                    @endif
                    <hr />
                @endif
                @if(count($pending))
                <div class="table-responsive">
                <table id="example" class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th width="30%">Route # / Description</th>
                        <th width="30%">Document Type</th>
                        <th>From</th>
                        <th>Duration</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pending as $doc)
                        <tr data-id="{{ $doc->id }}">
                            <td>
                                {{--<a class="title-info" data-route="{{ $doc->route_no }}" data-link="{{ asset('/document/info/'.$doc->route_no.'/'.$doc->doc_type) }}" href="#document_info" data-toggle="modal">--}}
                                <a href="#track" class="title-info" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-toggle="modal">
                                {{ $doc->route_no }}
                                </a>
                                <br />
                                <small class="text-info">
                                    {!! nl2br($doc->description) !!}
                                </small>
                            </td>
                            <td>{{ Doc::docTypeName($doc->doc_type) }}</td>
                            <?php
                                $user = User::find($doc->delivered_by);
                                $name = 'Name of Section';
                                $section = 'Unconfirmed';
                                if($user){
                                    $name = $user->fname.' '.$user->lname;
                                    $section = \App\Section::find($user->section)->description;
                                }else{
                                    $name = $doc->code;
                                    $x = \App\Tracking_Details::where('received_by',0)
                                            ->where('id','<',$doc->id)
                                            ->where('route_no',$doc->route_no)
                                            ->first();
                                    $string = $x->code;
                                    $temp1   = explode(';',$string);
                                    $temp2   = array_slice($temp1, 1, 1);
                                    $section_id = implode(',', $temp2);
                                    $name = \App\Section::find($section_id)->description;
                                }
                            ?>

                            <td>{{ $name }}<br /><small class="text-info"><em>({{ $section }})</em></small> </td>
                            <td>
                                {{ Rel::duration($doc->date_in) }}<br />
                            </td>
                            <td class="action">
                                <?php
                                    $section_id = \App\Section::find(Session::get('auth')->section)->id;
                                ?>
                                @if($doc->code=='temp;'.$section_id)
                                    <?php
                                        $diff = Rel::hourDiff($doc->date_in);
                                    ?>
                                    <a href="{{ asset('document/accept/'.$doc->id) }}" class="btn btn-success btn-sm btn-accept">Accept</a><br />
                                    @if($diff>=0.00)
                                    <a href="#" class="btn btn-warning btn-sm btn-return" data-id="{{ $doc->id }}">Return</a>
                                    @endif
                                @elseif($doc->code=='accept;'.$section_id)
                                    <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $doc->id }}" data-route_no="{{ $doc->route_no }}" onclick="putRoute($(this))" type="button" class="btn btn-info btn-sm">Release To</button>
                                    <a href="#remove_pending" data-link="{{ asset('document/removepending/'.$doc->id) }}" data-id="{{ $doc->id }}" class="btn btn-warning btn-sm">Cycle End</a>
                                @elseif($doc->code=='return;'.$section_id)
                                    <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $doc->id }}" data-route_no="{{ $doc->route_no }}" onclick="putRoute($(this))" type="button" class="btn btn-info btn-sm">Release To</button>
                                    <a href="#remove_pending" data-link="{{ asset('document/removepending/'.$doc->id) }}" data-id="{{ $doc->id }}" class="btn btn-warning btn-sm">Cycle End</a>
                                @else

                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>

                @else
                <div class="alert alert-info">
                    <i class="fa fa-info"></i> No pending document!
                </div>
                @endif
                <div class="clearfix"></div><br>
            </form>
            <hr />
            <div class="accepted-list">

            </div>
        </div>
    </div>
@include('modal.release_modal')
@endsection

@section('js')
    <script src="<?php echo asset('resources/plugin/dataTable/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo asset('resources/plugin/dataTable/js/dataTables.bootstrap.min.js');?>"></script>
    @include('js.release_js')
@endsection