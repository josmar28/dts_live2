@extends('layouts.app')
@section('content')
    <div class="col-lg wrapper">
        <div class="alert alert-jim">
            <?php
            Use App\Division;
            Use App\Section;
            Use App\Designation;
            $count = 0;
            $total = 0;
            ?>
            <link href="{{ asset('resources/assets/css/print.css') }}" rel="stylesheet">
            <style>
                #border{
                    border-collapse: collapse;
                    border: none;
                }
                #border-top{
                    border-collapse: collapse;
                    border-top: none;
                }
                #border-right{
                    border-collapse: collapse;
                    border-right: none;
                }
                #border-bottom{
                    border-collapse: collapse;
                    border-bottom: none;
                }
                #border-left{
                    border-collapse: collapse;
                    border-left: none;
                }
                .align{
                    text-align: center;
                }
                .align-top{
                    vertical-align: top;
                }
                .table1 {
                    width: 100%;
                }
                .table1 td {
                    border:1px solid #000;
                }
                small{
                    color:red;
                }
                hr {
                    height: 10px;
                    border: 0;
                    box-shadow: 0 10px 10px -10px #8c8c8c inset;
                }
            </style>
            <form method="post" id="form" action="{{ asset('prr_meal_update') }}">
                {{ csrf_field() }}
                <span id="getDesignation" data-link="{{ asset('getDesignation') }}"></span>
                <span id="url" data-link="{{ asset('prr_meal_append') }}"></span>
                <span id="update_history" data-link="{{ asset('prr_meal_history') }}"></span>
                <span id="token" data-token="{{ csrf_token() }}"></span>
                <input type="hidden" name="doc_type" value="PRR_S">
                <input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
                <div class="modal-body">
                    <div class="content-wrapper">
                        <!-- Main content -->
                        <section class="invoice">
                            <div class="table-responsive">
                                @if(Session::get('updated'))
                                    <div class="alert alert-info">
                                        <i class="fa fa-check"></i> Successfully Updated!
                                    </div>
                                    <?php Session::forget('updated'); ?>
                                @endif
                                <table class="letter-head" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td id="border" class="align"><img src="{{ asset('resources/img/doh.png') }}" width="100"></td>
                                        <td width="90%" id="border">
                                            <div class="align" style="margin-top:-10px;">
                                                <center>
                                                    Republic of the Philippinesss<br>
                                                    <strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO. VII</strong><br>
                                                    Osmeña Boulevard, Cebu City, 6000 Philippines<br>
                                                    Regional Director’s Office Tel. No. (032) 253-6355 Fax No. (032) 254-0109<br>
                                                    Official Website: http://www.ro7.doh.gov.ph Email Address: dohro7@gmail.com<br>
                                                </center>
                                            </div>
                                        </td>
                                        <td id="border" class="align"><img src="{{ asset('resources/img/ro7.png') }}" width="100"></td>
                                    </tr>
                                </table>
                                <table class="letter-head" cellpadding="0" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <td colspan="7" class="align">
                                            <strong>PURCHASE REQUEST</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Department:</td>
                                        <td colspan="2">{{ Division::find(Session::get('auth')->division)->description }}</td>
                                        <td colspan="2">PR No:</td>
                                        <td>Date:<input class="form-control" name="prepared_date" value="{{ substr($tracking->prepared_date,5,2).'/'.substr($tracking->prepared_date,8,2).'/'.substr($tracking->prepared_date,0,4) }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Section:</td>
                                        <td colspan="2">{{ Section::find(Session::get('auth')->section)->description }}</td>
                                        <td colspan="2">SAI No.:</td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Unit:</td>
                                        <td colspan="2"></td>
                                        <td colspan="2">ALOBS No.:</td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td><b>Option</b></td>
                                        <td><b>Qty</b></td>
                                        <td><b>Unit of Issue</b></td>
                                        <td><b>Item Description</b></td>
                                        <td><b>Stock No.</b></td>
                                        <td><b>Unit Cost</b></td>
                                        <td><b>Estimated Cost</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="global_title align">
                                            <strong><i>Global Title</i></strong>
                                            <input type="text" name="global_title" id="global_title" class="form-control" value="{{ $prr_meal_logs->global_title }}" onkeyup="trapping()" required>
                                            <small id="E_global_title">required!</small>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody class="input_fields_wrap">
                                    <?php
                                    foreach($meal as $row):
                                    $total += $row->estimated_cost;
                                    $count++;
                                    ?>
                                    <tr id="{{ $count }}">
                                        <input type="hidden" value="{{ $row->id }}" name="pr_id">
                                        <td id="border-bottom" class="align-top">
                                            <button type="button" value="{{ $count }}" onclick="erase($(this))" class="btn-sm"><small><i class="glyphicon glyphicon-remove"></i></small></button>
                                        </td>
                                        <td id="border-bottom">

                                        </td>
                                        <td id="border-bottom">

                                        </td>
                                        <td id="border-bottom" class="align-top" width="40%">
                                            <div class="{{ 'description'.$count }}">
                                                <strong><i>Description</i></strong>
                                                <textarea class="textarea" placeholder="Place some text here" style="width: 100%;font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description[]" id="{{ 'description'.$count }}" onkeyup="trapping()" required>
                                                    {{ $row->description }}
                                                </textarea>
                                                <small id="{{ 'E_description'.$count }}"></small>
                                            </div>
                                            <div class="{{ 'expected'.$count }}">
                                                <strong><i>Expected:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="text" name="expected[]" id="{{ 'expected'.$count }}" value="{{ $row->expected }}" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" style="width: 50%;display: inline" required>
                                                <small id="{{ 'E_expected'.$count }}">required!</small>
                                            </div>
                                            <div class="{{ 'date_time'.$count }}" style="margin-top: 2%">
                                                <strong><i>Date and Time:</i></strong> &nbsp;&nbsp;&nbsp;
                                                <input type="text" name="date_time[]" value="{{ $row->date_time }}" id="{{ 'date_time'.$count }}" class="form-control" onkeyup="trapping(event,true)" style="width: 50%;display: inline" required>
                                                <small id="{{ 'E_date_time'.$count }}">required!</small>
                                            </div>
                                        </td>
                                        <td id="border-bottom"></td>
                                        <td id="border-bottom" class="{{ 'unit_cost'.$count }} align-top"><input type="text" name="unit_cost[]" id="{{ 'unit_cost'.$count }}" value="{{ $row->unit_cost }}"  class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required><small id="{{ 'E_unit_cost'.$count }}">required!</small></td>
                                        <td id="border-bottom" class="{{ 'estimated_cost'.$count }} align-top">
                                            <input type="hidden" name="estimated_cost[]" id="{{ 'estimated_cost'.$count }}" value="{{ $row->estimated_cost }}"  class="form-control">
                                            <strong style="color:green;">&#x20b1;</strong><strong style="color:green" id="{{ 'e_cost'.$count }}">{{ $row->estimated_cost }} </strong>
                                        </td>
                                    </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"><a onclick="add();" href="#">Add new</a></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"><br><br> Prepared By:<br><br><u>{{ Session::get('auth')->fname.' '.Session::get('auth')->mname.' '.Session::get('auth')->lname }}</u><br>{{ Designation::find(Session::get('auth')->designation)->description }}</td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                        <td id="border-top"></td>
                                    </tr>
                                    <tr>
                                        <td class="align" colspan="6"><b>TOTAL</b></td>
                                        <td class="align-top">
                                            <input type="hidden" id="count" value="{{ $count }}">
                                            <input type="hidden" name="amount" id="amount">
                                            <strong style="color: red;">&#x20b1;</strong><strong style="color:red" id="total">{{ $total }}</strong>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row" style="padding: 2%">
                                <div class="btn-group btn-group-md pull-right">
                                    <button class="btn btn-info" type="button" style="margin-left: 5%" onclick="update_history()">
                                        <i class="fa fa-history"></i> Update History</button>
                                </div>
                                <div class="btn-group btn-group-md pull-right">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-edit"></i> Update </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-8">
                                    <h3>Certification</h3>
                                    <address>This is to certify that dilligent efforts have been exerted to ensure that the price/s indicated above(in relation to the specifications) is/are within the prevailing market price/s.
                                    </address>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Requested By:</label>
                                        <div class="col-sm-10">
                                            <input id="section_head" class="form-control" value="{{ \App\Users::find($tracking->requested_by)->fname.' '.App\Users::find($tracking->requested_by)->mname.' '.App\Users::find($tracking->requested_by)->lname }}" readonly>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Designation:</label>
                                        <div class="col-sm-10">
                                            <input id="section_head" class="form-control" value="{{ App\Designation::find(\App\User::find($tracking->requested_by)->designation)->description }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="form-group">
                                        <label for="purpose" class="col-sm-2 control-label">Purpose:</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="purpose" name="purpose" readonly>{{ $tracking->purpose }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="form-group">
                                        <label for="chargeable" class="col-sm-2 control-label">Chargeable to:</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="charge_to" readonly>{{ $tracking->source_fund }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <center>
                                        <h4><strong class="lean">Recommending Approval:</strong></h4>
                                    </center>
                                </div>
                                <div class="col-md-6">
                                    <center>
                                        <h4><strong class="lean">Approved:</strong></h4>
                                    </center>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="col-sm-4 control-label">Printed Name:</label>
                                    <div class="col-sm-10">
                                        <input id="section_head" class="form-control" value="{{ \App\Users::find($tracking->division_head)->fname.' '.App\Users::find($tracking->division_head)->mname.' '.App\Users::find($tracking->division_head)->lname }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <center>
                                        <strong>JAIME S. BERNADAS, MD, MGM, CESO III</strong><br>
                                        Director IV
                                    </center>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="col-sm-4 control-label">Designation:</label>
                                    <div class="col-sm-10">
                                        <input id="division_head" class="form-control" value="{{ App\Designation::find(\App\User::find($tracking->division_head)->designation)->description }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- this row will not appear when printing -->
                        </section>
                    </div>
                </div>
            </form>
            <form action="{{ asset('prr_meal_pdf') }}" method="get" target="_blank">
                <div class="btn-group btn-group-lg;">
                    <button class="btn btn-primary" type="submit" >
                        <i class="fa fa-download"></i> Generate-PDF</button>
                </div>
            </form>
            <!-- /.content -->
            <div class="clearfix"></div>
        </div>
    </div>
    {{--SIDE BAR--}}
@endsection

@section('js')
    <script>
        ///PLUGIN
        $(".textarea").wysihtml5();
        $('.datepickercalendar').datepicker({
            autoclose: true
        });
        ///END PLUGIN
        var count = $("#count").val();
        var limit = 10;
        trapping(event,false);

        var ok = "";
        function add(){
            event.preventDefault();
            ok = "true";
            var wrapper= $(".input_fields_wrap"); //Fields wrapper

            trapping();

            if(count < limit) {
                count++;
                var url = $("#url").data('link');
                url += "?count=" + count;
                $.get(url, function (result) {
                    $(wrapper).append(result);
                });
            }
        }

        function trapping(event,flag){
            if(flag)
                key_code(event);
            var estimated_cost = 0;
            var total = 0;
            $("#global_title").val() == '' ? ($(".global_title").addClass("has-error"),$("#E_global_title").show()) : ($(".global_title").removeClass("has-error"),$("#E_global_title").hide());
            for(var i=1; i<=count; i++){
                if($("#expected"+i).val() == '' || $("#date_time"+i).val() == '' || $("#unit_cost"+i).val() == '' || $("#description"+i).val() == '') {
                    ok = "false";
                }

                $("#expected"+i).val() == '' ? ($(".expected"+i).addClass("has-error"),$("#E_expected"+i).show()) :($(".expected"+i).removeClass("has-error"),$("#E_expected"+i).hide()) ;
                $("#date_time"+i).val() == '' ? ($(".date_time"+i).addClass("has-error"),$("#E_date_time"+i).show()) : ($(".date_time"+i).removeClass("has-error"),$("#E_date_time"+i).hide());
                $("#unit_cost"+i).val() == '' ? ($(".unit_cost"+i).addClass("has-error"),$("#E_unit_cost"+i).show()) : ($(".unit_cost"+i).removeClass("has-error"),$("#E_unit_cost"+i).hide());

                var noComma = parseFloat(numeral($("#unit_cost"+i).val()).format('0,0.00').replace(/,/g, ''));
                $("#expected"+i).val() && $("#unit_cost"+i).val() !== '' ? (parseFloat($("#estimated_cost"+i).val($("#expected"+i).val()*noComma))) : $("#estimated_cost"+i).val('');
                $("#expected"+i).val() && $("#unit_cost"+i).val() !== '' ? ($("#e_cost"+i).text(numeral($("#expected"+i).val()*noComma).format('0,0.00')),estimated_cost = $("#estimated_cost"+i).val()) : ($("#e_cost"+i).text(''),estimated_cost = 0);

                total += parseFloat(estimated_cost);
            }
            $("#total").text(numeral(total).format('0,0.00'));
            $("#amount").val(numeral(total).format('0,0.00'));
        }

        function key_code(e){
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

        function get_designation(result,request){
            var url = $("#getDesignation").data('link')+'/'+result.val();
            $.get(url, function(designation){
                request == 'section' ?
                        result.val() ? $("#section_head").val(designation) : $("#section_head").val('') :
                        result.val() ? $("#division_head").val(designation) : $("#division_head").val('');
            });
        }

        function haha(){
            console.log(count);
        }

        function erase(result){
            limit++;
            $("#"+result.val()).remove();
            trapping();
        }

        document.onkeydown = function(evt) {
            evt = evt || window.event;
            var isEscape = false;
            if ("key" in evt) {
                isEscape = (evt.key == "Escape" || evt.key == "Esc");
            } else {
                isEscape = (evt.keyCode == 27);
            }
            if (isEscape) {
                count = $("#count").val();
            }
        };

        function update_history(){
            $('#document_form').modal('show');
            $('.modal_content').html(loadingState);
            $('.modal-title').html('Update History Logs');
            var url = $("#update_history").data('link');
            setTimeout(function() {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('.modal_content').html(data);
                        $('#reservation').daterangepicker();
                        var datePicker = $('body').find('.datepicker');
                        //Date picker
                        $('.datepickercalendar').datepicker({
                            autoclose: true
                        });
                        $('input').attr('autocomplete', 'off');
                    }
                });
            },1000);
        }
    </script>
@endsection