<?php
Use App\Division;
Use App\Section;
Use App\Designation;
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
    .align-bottom{
        vertical-align: bottom;
    }
    .table1 td {
        border:1px solid #000;
    }
    small{
        color:red;
    }
</style>
<form method="POST" id="form" action="{{ asset('prr_meal_post') }}">
    {{ csrf_field() }}
    <span id="getDesignation" data-link="{{ asset('getDesignation') }}"></span>
    <span id="url" data-link="{{ asset('prr_meal_append') }}"></span>
    <span id="category_url" data-link="{{ asset('prr_meal_category') }}"></span>
    <span id="token" data-token="{{ csrf_token() }}"></span>
    <input type="hidden" name="doc_type" value="PRR_M">
    <input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
    <!--
    <div class="modal-body">
        <div class="content-wrapper">
            <section class="invoice">
                <div class="table-responsive">
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
                            <td>Date:<input class="form-control datepickercalendar" name="prepared_date" required></td>
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
                            <td class="align" width="50%"><b>Item</b></td>
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
                                <input type="text" name="global_title" id="global_title" class="form-control" onkeyup="trapping()" required>
                                <small id="E_global_title">required!</small>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody class="input_fields_wrap">
                        <tr>
                            <td id="border-bottom" >

                            </td>
                            <td id="border-bottom" >

                            </td>
                            <td id="border-bottom" >

                            </td>
                            <td id="border-bottom" class="align-top" width="40%">
                                <div class="description1">
                                    <strong><i>Description</i></strong>
                                    <textarea class="textarea" placeholder="Place some text here" style="width: 100%;font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description[]" id="description1"  onkeyup="trapping()" required></textarea>
                                    <small id="E_description1"></small>
                                </div>
                                <div class="expected1">
                                    <strong><i>Expected:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" name="expected[]" id="expected1" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" style="width: 50%;display: inline" required>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <small id="E_expected1">required!</small>
                                </div>
                                <div class="date_time1" style="margin-top: 2%">
                                    <strong><i>Date and Time:</i></strong> &nbsp;&nbsp;&nbsp;
                                    <input type="text" name="date_time[]" id="date_time1" class="form-control" onkeyup="trapping(event,true)" style="width: 50%;display: inline" required>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <small id="E_date_time1">required!</small>
                                </div>
                                <div id="category_append">
                                    <div style="margin-top: 2%">
                                        <strong><i>Category:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <select name="category[category][0]" id="category1" class="form-control" style="width: 50%;display: inline;">
                                            <option value="">Select Category</option>
                                            <option value="AM Snacks">AM Snacks</option>
                                            <option value="PM Snacks">PM Snacks</option>
                                            <option value="Buffet Lunch">Buffet Lunch</option>
                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <a onclick="add_category();" class="pull-left" href="#" style="margin-top: 2%;"><i class="fa fa-plus"></i> Add Category</a>
                            </td>
                            <td id="border-bottom"></td>
                            <td id="border-bottom" class="unit_cost1 align-bottom">
                                <div id="unit_cost_append" style="margin-bottom: 30%">
                                    <div style="margin-bottom: 10%;">
                                        <input type="text" name="unit_cost[]" id="unit_cost1" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required>
                                    </div>
                                </div>
                            </td>
                            <td id="border-bottom" class="estimated_cost1 align-bottom">
                                <div id="estimated_cost_append" style="margin-bottom: 30%">
                                    <div style="margin-bottom: 25%;">
                                        <input type="hidden" name="estimated_cost[]" id="estimated_cost1" class="form-control">
                                        <strong style="color:green;">&#x20b1;</strong><strong style="color:green" id="e_cost1"></strong>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <tbody>
                        <tr>
                            <td id="border-top"></td>
                            <td id="border-top"></td>
                            <td id="border-top"></td>
                            <td id="border-top"></td>
                            <td id="border-top"></td>
                            <td id="border-top"></td>
                            <td id="border-top"><a onclick="add();" href="#"><i class="fa fa-plus"></i> Add Item</a></td>
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
                                <input type="hidden" name="amount" id="amount">
                                <strong style="color: red;">&#x20b1;</strong><strong style="color:red" id="total"></strong>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h3>Certification</h3>
                        <address>This is to certify that dilligent efforts have been exerted to ensure that the price/s indicated above(in relation to the specifications) is/are within the prevailing market price/s.
                        </address>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Requested By:</label>
                            <div class="col-sm-10">
                                <select  class="form-control" onchange="get_designation($(this),'section')" name="requested_by" required>
                                    <option value="">Select Name</option>
                                    @foreach($section_head as $row)
                                        <option value="{{ $row['id'] }}">{{ $row['fname'].' '.$row['mname'].' '.$row['lname'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Designation:</label>
                            <div class="col-sm-10">
                                <input id="section_head" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="purpose" class="col-sm-2 control-label">Purpose:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="purpose" name="purpose" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="chargeable" class="col-sm-2 control-label">Chargeable to:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="charge_to" required></textarea>
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
                            <h4><strong class="lean text-center">Approved:</strong></h4>
                        </center>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-6">
                        <label class="col-sm-4 control-label">Printed Name:</label>
                        <div class="col-sm-10">
                            <select class="form-control" onchange="get_designation($(this),'division');" name="division_head" required>
                                <option value="">Select Name</option>
                                @foreach($division_head as $row)
                                    <option value="{{ $row['id'] }}">{{ $row['fname'].' '.$row['mname'].' '.$row['lname'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <center>
                            <strong>JAIME S. BERNADAS, MD, MGM, CESO III</strong><br>
                            Director IV
                        </center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-sm-4 control-label">Designation:</label>
                        <div class="col-sm-10">
                            <input id="division_head" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row no-print">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-success pull-left" onclick="haha();" style="margin-right: 5px;">
                            <i class="fa fa-send"></i> Submit
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div> -->
    <div id="category_append">
        <div style="margin-top: 2%">
            <strong><i>Category:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="category[][]" id="category1" class="form-control" style="width: 50%;display: inline;">
                <option value="">Select Category</option>
                <option value="AM Snacks">AM Snacks</option>
                <option value="PM Snacks">PM Snacks</option>
                <option value="Buffet Lunch">Buffet Lunch</option>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div style="margin-top: 2%">
            <strong><i>Category:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="category[][]" id="category1" class="form-control" style="width: 50%;display: inline;">
                <option value="">Select Category</option>
                <option value="AM Snacks">AM Snacks</option>
                <option value="PM Snacks">PM Snacks</option>
                <option value="Buffet Lunch">Buffet Lunch</option>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>
    <a onclick="add_category();" class="pull-left" href="#" style="margin-top: 2%;"><i class="fa fa-plus"></i> Add Category</a>
    <div class="row no-print">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-success pull-left" onclick="haha();" style="margin-right: 5px;">
                <i class="fa fa-send"></i> Submit
            </button>
        </div>
    </div>
</form>
<!-- /.content -->
<div class="clearfix"></div>
<script>
    var width = $("#my_modal").width() + 100;
    $("#my_modal").css("width", width);
    $(".textarea").wysihtml5();

    var count = 1;
    var limit = 10;
    var ok = "";
    function add(){
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
            $("#my_modal").css("width", width-100);
        }
    };

    ///CATEGORY
    var category_count = 1;
    function add_category()
    {
        category_count++;
        var category_url = $("#category_url").data('link')+"?type=category&category_count=" + category_count;
        $.get(category_url,function(result){
            $("#category_append").append(result);
        });
        category_url = $("#category_url").data('link')+"?type=unit_cost&category_count=" + category_count;
        $.get(category_url,function(result){
            $("#unit_cost_append").append(result);
        });
        category_url = $("#category_url").data('link')+"?type=estimated_cost&category_count=" + category_count;
        $.get(category_url,function(result){
            $("#estimated_cost_append").append(result);
        });
    }

    function remove_category($value)
    {
        for(var i=0;i<=category_count;i++){
            $("#"+$value.data("value")).remove();
        }
    }

</script>