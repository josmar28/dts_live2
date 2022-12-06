<?php
use App\Http\Controllers\ReleaseController as Rel;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="col-md-9 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">Reported Documents</h2>
            <form id="accept_form" method="post">
                {{ csrf_field() }}
                @if(count($reported))
                    <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th width="8%"></th>
                            <th>Route No / Barcode</th>
                            <th>Delivered Date</th>
                            <th>Delivered By</th>
                            <th>Duration</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reported as $doc)
                            <tr>
                                <td>
                                    <a href="#track" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-route="{{ $doc->route_no }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a>
                                </td>
                                <td>
                                    <a class="title-info" data-route="{{ $doc->route_no }}" data-link="{{ asset('/document/info/'.$doc->route_no.'/'.$doc->doc_type) }}" href="#document_info" data-toggle="modal">
                                        {{ $doc->route_no }}
                                    </a>
                                </td>
                                <td>
                                    {{ date('M d, Y',strtotime($doc->date_reported)) }}<br/>
                                    {{ date('h:i:s A',strtotime($doc->date_reported)) }}
                                </td>
                                <?php
                                    $user = User::find($doc->reported_by);
                                ?>
                                <td>{{ $user->fname.' '.$user->lname }}</td>
                                <td>{{ Rel::duration($doc->date_reported) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    {{ $reported->links() }}
                @else
                    <div class="alert alert-info">
                        <i class="fa fa-info"></i> No reported document!
                    </div>
                @endif
                <div class="clearfix"></div><br>
            </form>
            <hr />
            <div class="accepted-list">

            </div>
        </div>
    </div>
    @include('sidebar')
@endsection

@section('js')
@endsection