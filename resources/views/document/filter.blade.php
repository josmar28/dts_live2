@extends('layouts.app')

@section('content')
    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Filter Document Types</h2>
        <a href="#filter_modal" data-toggle="modal" class="btn btn-info btn-sm btn-flat" onclick="FilterBody('empty')">
                            <i class="fa fa-hospital-o"></i> Add Document
                        </a>
        <div class="page-divider"></div>
            <div class="table-responsive">
                <input type="hidden" id="token" value="{{ csrf_token() }}">
                <input type="hidden" id="url" value="{{ asset('document/filter/update') }}">
                <table class="table table-bordered table-type  table-hover table-striped">
                <thead>
                <tr>
                    <th>Document Type</th>
                    <th>Document Description</th>
                    <th>description</th>
                    <th>amount</th>
                    <th>pr_no</th>
                    <th>po_no</th>
                    <th>purpose</th>
                    <th>source_fund</th>
                    <th>requested_by</th>
                    <th>route_to</th>
                    <th>route_from</th>
                    <th>supplier</th>
                    <th>event_date</th>
                    <th>event_location</th>
                    <th>event_participant</th>
                    <th>cdo_applicant</th>
                    <th>cdo_day</th>
                    <th>event_daterange</th>
                    <th>payee</th>
                    <th>item</th>
                    <th>dv_no</th>
                    <th>ors_no</th>
                    <th>fund_source_budget</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr>
                        <td> <a href="#filter_modal"
                                       data-toggle="modal"
                                       onclick="FilterBody('<?php echo $doc->id ?>')">
                                       {{ $doc->doc_type }}
                                    </a></td>
                        <td> {{ $doc->doc_description }}</td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'description' }}"
                            @if($doc->description==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                       
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'amount' }}"
                            @if($doc->amount==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'pr_no' }}"
                            @if($doc->pr_no==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'po_no' }}"
                            @if($doc->po_no==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'purpose' }}"
                            @if($doc->purpose==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'source_fund' }}"
                            @if($doc->source_fund==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'requested_by' }}"
                            @if($doc->requested_by==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'route_to' }}"
                            @if($doc->route_to==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'route_from' }}"
                            @if($doc->route_from==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'supplier' }}"
                            @if($doc->supplier==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'event_date' }}"
                            @if($doc->event_date==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'event_location' }}"
                            @if($doc->event_location==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'event_participant' }}"
                            @if($doc->event_participant==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'cdo_applicant' }}"
                            @if($doc->cdo_applicant==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'cdo_day' }}"
                            @if($doc->cdo_day==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'event_daterange' }}"
                            @if($doc->event_daterange==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'payee' }}"
                            @if($doc->payee==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'item' }}"
                            @if($doc->item==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'dv_no' }}"
                            @if($doc->dv_no==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'ors_no' }}"
                            @if($doc->ors_no==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                        <td><input data-type="{{ $doc->doc_type }}" type="checkbox" class="update_filter flat-red" data-column="{{ 'fund_source_budget' }}"
                            @if($doc->fund_source_budget==1)
                                {{ 'checked' }}
                                    @endif
                            >
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
    </div>

@endsection
@section('js')
<script>
           $('input.update_filter').click(function(){
        if($(this).prop('checked') == true){
            var data = {
                'column':$(this).data('column'),
                'doc_type':$(this).data('type'),
                'value':1,
                '_token':$("#token").val()
            };
            }
        if($(this).prop('checked') == false){
            var data = {
                'column':$(this).data('column'),
                'doc_type':$(this).data('type'),
                'value':0,
                '_token':$("#token").val()
            };
            }
 
            
            var url = $('#url').val();
            $('.loading').show();
            setTimeout(function(){
                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function(data) {
                        console.log(data);
                        $('.loading').hide();
                    }
                });
            },1000);
        });

    function FilterBody(doc_id){
        console.log(doc_id);
        var url = "<?php echo asset('document/filter_body/update'); ?>";
        var json = {
            "doc_id" : doc_id,
            "_token" : "<?php echo csrf_token(); ?>"
        };
        $.post(url,json,function(result){
            $(".filter_body").html(result);
        });
    }

    
    function docDelete(doc_type){
            $(".doc_type").val(doc_type);
        }

        @if(Session::get('filter'))
        Lobibox.notify('success', {
            title: "",
            msg: "<?php echo Session::get("filter_message"); ?>",
            size: 'mini',
            rounded: true
        });
        <?php
        Session::put("filter",false);
        Session::put("filter_message",false)
        ?>
        @endif

        @if(Session::get('filter_delete'))
        Lobibox.notify('success', {
            title: "",
            msg: "<?php echo Session::get("filter_message_delete"); ?>",
            size: 'mini',
            rounded: true
        });
        <?php
        Session::put("filter_delete",false);
        Session::put("filter_message_delete",false)
        ?>
        @endif

    </script>
    @endsection
@section('plugin')
    <script src="{{ asset('resources/plugin/iCheck/icheck.min.js') }}"></script>
    <script>
        var $table = $('.table-type');
        var $fixedColumn = $table.clone().insertBefore($table).addClass('fixed-column');

        $fixedColumn.find('th:not(:first-child),td:not(:first-child)').remove();

        $fixedColumn.find('tr').each(function (i, elem) {
            $(this).height($table.find('tr:eq(' + i + ')').height()-1.5);
        });

        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });

        $('.update_filter').on('ifChecked',function(){
            var data = {
                'column':$(this).data('column'),
                'doc_type':$(this).data('type'),
                'value':1,
                '_token':$("#token").val()
            };

            var url = $('#url').val();
            $('.loading').show();
            setTimeout(function(){
                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function(data) {
                        console.log(data);
                        $('.loading').hide();
                    }
                });
            },1000);
        });
        $('.update_filter').on('ifUnchecked',function(){
            var data = {
                'column':$(this).data('column'),
                'doc_type':$(this).data('type'),
                'value':0,
                '_token':$("#token").val()
            };
            var url = $('#url').val();
            $('.loading').show();
            setTimeout(function(){
                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function(data) {
                        console.log(data);
                        $('.loading').hide();
                    }
                });
            },500);
        });
      
    </script>
    <script>
        var down=false;
        var scrollLeft=0;
        var x = 0;

        $('.table-responsive').mousedown(function(e) {
            down = true;
            scrollLeft = this.scrollLeft;
            x = e.clientX;
        }).mouseup(function() {
            down = false;
        }).mousemove(function(e) {
            if (down) {
                this.scrollLeft = scrollLeft + x - e.clientX;
            }
        }).mouseleave(function() {
            down = false;
        });
    </script>
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
        }
        .table tr td {
            text-align: center;
        }
    </style>
@endsection

