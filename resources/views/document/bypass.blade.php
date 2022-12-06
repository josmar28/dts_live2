<?php
use App\Users;
use App\Section;
use App\Http\Controllers\DocumentController as Doc;
use App\Http\Controllers\AccessController as Access;

// dd($documents);
?>
@extends('layouts.app')

@section('content')

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
    <h2 class="page-header">Bypass Documents</h2>
    <form class="form-inline" method="POST" action="{{ asset('chd12report/trackingflow') }}" onsubmit="return searchDocument();" id="searchForm">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Quick Search" id="keyword" name="keyword" value="@if(isset($keyword)){{ $keyword }}@endif" autofocus>
            <input type="text" class="form-control" id="daterange" name="daterange" value="@if(isset($daterange)){{ $daterange }}@endif" placeholder="Input date range here..." required>
            
            <select name="section" id="section" class="form-control" style="width:300px">
            <option value="">Select Section</option>
                 <?php $section = \App\Section::all(); ?>
                     @foreach($section as $sec)
                          <option @if (isset($section1)) {{ ($section1 == $sec->id ? 'selected' : '') }} @endif value="{{ $sec->id }}">{{ $sec->description }}</option>
                     @endforeach   
            </select>

            <select id="doc_type" name="doc_type" class="form-control" style="width:300px;">
           <option value="">Select Document Type</option>
           <?php
            $doc_types = App\Tracking_Filter::where('doc_type', '!=' , 'GENERAL')
            ->where('doc_type', '!=' , 'PRC')
            ->where('doc_type', '!=' , 'PRR_M')
            ->orderby('doc_description','asc')
            ->get();
           ?>
             @foreach($doc_types as $row)
          <option @if(isset($doc_type)) {{ ($doc_type == $row->doc_type ? 'selected' : '') }} @endif value="{{ $row->doc_type }}"> {{ $row->doc_description }}</option>
              @endforeach
         </select>
         </select>
       
       
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
        </div>
    </form>
    <div class="clearfix"></div>
    <div class="page-divider"></div>
    @if(count($documents))
    <div class="table-responsive">
        <table class="table table-list table-hover table-striped ">
            <thead>
            <tr>
                <th width="8%"></th>
                <th width="20%">Route #</th>
                <th width="15%">Prepared Date</th>
                <th width="15%">Prepared By</th>
                <th width="20%">Document Type</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @for($i=1;$i<count($documents)+1;$i++)
                <tr>
                @if($documents[$i]->status == 1)
                <td><a href="#track" data-link="{{ asset('document/track/'.$documents[$i]->route_no) }}" data-route="{{ $documents[$i]->route_no }}" data-toggle="modal" class="btn btn-sm btn-danger col-sm-12"><i class="fa fa-line-chart"></i> Track</a></td>
                @else
                
                <td><a href="#track" data-link="{{ asset('document/track/'.$documents[$i]->route_no) }}" data-route="{{ $documents[$i]->route_no }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a></td>
                @endif
               
                <td><a class="title-info" data-route="{{ $documents[$i]->route_no }}" data-link="{{ asset('/document/info/'.$documents[$i]->route_no.'/'.$documents[$i]->doc_type) }}" href="#document_info" data-toggle="modal">{{ $documents[$i]->route_no }}</a></td>
                <td>{{ date('M d, Y',strtotime($documents[$i]->prepared_date)) }}<br>{{ date('h:i:s A',strtotime($documents[$i]->prepared_date)) }}</td>
                <td>
                    <?php
                        if($user = Users::find($documents[$i]->prepared_by)){
                            $firstname = $user->fname;
                            $lastname = $user->lname;
                            if($tmp = Section::find($user->section))
                                $section = $tmp->description;
                            else 
                                $section = 'No Section';
                        } else{
                            $firstname = "No Firstname";
                            $lastname = "No Lastname";
                            $section = 'No Section';
                        }
                    ?>
                    {{ $firstname }}
                    {{ $lastname }}
                    <br>
                    <em>({{ $section }})</em>
                </td>
                <?php
                    $doc_type = '';
                    if($documents[$i]->doc_type){
                        $doc_type = \App\Http\Controllers\DocumentController::docTypeName($documents[$i]->doc_type);
                    }
                ?>
                <td>{{ $doc_type }}</td>
                <td>
                    @if($documents[$i]->doc_type == 'PRR_S')
                        {!! nl2br($documents[$i]->purpose) !!}
                    @else
                        {!! nl2br($documents[$i]->purpose) !!}
                    @endif
                </td>
            </tr>
            @endfor
            </tbody>
        </table>
    </div>
            <!-- <div id="bypass_form">
          
            </div> -->
    @else
    <div class="alert alert-danger">
        <strong><i class="fa fa-times fa-lg"></i> No documents found! </strong>
    </div>
    @endif
</div>
@endsection
@section('js')
<script>
    $('#daterange').daterangepicker();
    $(document).ready( function () {
        $('#inputText').on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            changePage(page);
        });
    });

    function changePage(page) {
        var url = "<?php echo asset('chd12report/trackingflow');?>";
        url = url + "?page=" + page;

        var json = {
            "_token" : "<?php echo csrf_token(); ?>",
            "pagination_table" : "true",
            "keyword" : $('#keyword').val(),
            "range" : $('#daterange').val(),
            "doc_type" : $('#doc_type').val(),
            "section" : $('#section').val()
        };
        $.post(url,json,function(result){
            
        });
    }
$('.filter-division2').on('change',function(){
        var id = $(this).val();
        var url = "<?php echo asset('getsections/');?>";
        $('.loading').show();
        $('.filter_section2').html('<option value="">Select section...</option>')
        $.ajax({
            url: url+'/'+id,
            type: "GET",
            success: function(sections){
                jQuery.each(sections,function(i,val){
                    $('.filter_section2').append($('<option>', {
                        value: val.id,
                        text: val.description
                    }));
                    $('.filter_section2').chosen().trigger('chosen:updated');
                    $('.filter_section2').siblings('.chosen-container').css({border:'2px solid red'});
                });
                $('.loading').hide();
            }
        })
    });
</script>
@endsection
@section('plugin')
<script src="{{ asset('resources/plugin/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('resources/plugin/daterangepicker/daterangepicker.js') }}"></script>
<script>


    
    function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },2000);
    }

    function putAmount(amount){
        $('.amount').html(amount.val());
        if(amount.valueOf()==null){
            $('.amount').html('0');
        }
    }

    function preparedBy(input)
    {
        var name = input.val();
        $('input[name="fullNameC"]').val(name);
        $('input[name="fullNameD"]').val(name);
        $('input[name="fullNameE"]').val(name);
        $('input[name="fullNameH"]').val(name);
        console.log(name);
    }

    function position(input)
    {
        var name = input.val();
        $('input[name="positionC"]').val(name);
        $('input[name="positionD"]').val(name);
        console.log(name);
    }

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    function append()
    {
        var hr='';
        var mn = '';

        for(i=0;i<=12;i++){
            var tmp = pad(i,2);
            hr += '<option>'+tmp+'</option>';
        }
        for(i=0;i<60;i++){
            var tmp = pad(i,2);
            mn += '<option>'+tmp+'</option>';
        }
        $('#append').append('<tr>' +
            '<td><input type="date" name="date[]" class="form-control"></td>' +
            '<td colspan="2"><input type="text" name="visited[]" class="form-control"></td>' +
            '<td><select name="hourA[]" class="form-control append">' +
            hr +
            '</select>'+
            '<select name="minA[]" class="form-control">' +
            mn +
            '</select>'+
            '<select name="ampmA[]" class="form-control">' +
            '<option>AM</option>' +
            '<option>PM</option>' +
            '</select>'+
            '</td>' +
            '<td><select name="hourB[]" class="form-control append">' +
            hr +
            '</select>'+
            '<select name="minB[]" class="form-control">' +
            mn +
            '</select>'+
            '<select name="ampmB[]" class="form-control">' +
            '<option>AM</option>' +
            '<option>PM</option>' +
            '</select>'+
            '</td>' +
            '<td><input type="text" name="trans[]" class="form-control"></td>'+
            '<td><input type="text" name="transAllow[]" class="form-control"></td>'+
            '<td><input type="text" name="dailyAllow[]" class="form-control"></td>'+
            '<td><input type="text" name="perDiem[]" class="form-control"></td>'+
            '<td><input type="text" name="total[]" class="form-control"></td>'+
            '</tr>');
    }

    function subTotal(){
        var values = {};
        var total = $('input[name="total[]"]');
        var c = 0;
        total.each(function(){
            values[c] = total.val();
            c++;
        });
        console.log(values);
    }
</script>
@endsection

@section('css')
<link href="{{ asset('resources/plugin/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
@endsection

