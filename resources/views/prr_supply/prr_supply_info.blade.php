<?php
Use App\Division;
Use App\Section;
Use App\Users;
$count = 0;
$total = 0;
?>
<link href="{{ asset('resources/assets/css/print.css') }}" rel="stylesheet">
<style>
    #border-top{
        border-collapse: collapse;
        border-top: none;
    }
    #border-bottom{
        border-collapse: collapse;
        border-bottom: none;
    }
    .align{
        text-align: center;
    }
    .align-top{
        vertical-align: top;
    }
    small{
        color:red;
    }
</style>
<form method="post" id="form" action="{{ asset('prr_supply_update') }}">
    {{ csrf_field() }}
    <span id="getDesignation" data-link="{{ asset('getDesignation') }}"></span>
    <span id="url" data-link="{{ asset('prr_supply_append') }}"></span>
    <span id="token" data-token="{{ csrf_token() }}"></span>
    <input type="hidden" name="doc_type" value="PRR_S">
    <input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
    <input type="hidden" value="{{ $tracking->route_no }}" name="route_no">
    <div class="modal-body">
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="invoice">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="align"><img src="{{ asset('resources/img/doh.png') }}" width="100"></td>
                        <td width="90%" >
                            <div class="align small-text">
                                Republic of the Philippines<br>
                                DEPARTMENT OF HEALTH<br>
                                <strong>CENTRAL VISAYAS CENTER for HEALTH DEVELOPMENT</strong><br>
                                Osmeña Boulevard,Sambag II,Cebu City, 6000 Philippines<br>
                                Regional Director’s Office Tel. No. (032) 253-6355 Fax No. (032) 254-0109<br>
                                Official Website: http://www.ro7.doh.gov.ph Email Address: dohro7@gmail.com<br>
                            </div>
                        </td>
                        <td class="align"><img src="{{ asset('resources/img/f1.jpg') }}" width="100"></td>
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
                        <td colspan="3">PR Date: <input class="DatePicker" value="{{ $tracking->pr_date }}" name="pr_date" required></td>
                    </tr>
                    <tr>
                        <td colspan="2">Section/Unit:</td>
                        <td colspan="5">{{ Section::find(Session::get('auth')->section)->description }}</td>
                    </tr>
                    <tr>
                        <td ><b>Option</b></td>
                        <td ><b>Unit of Issue</b></td>
                        <td width="40%"><b>Item Description</b></td>
                        <td ><b>Qty</b></td>
                        <td colspan="2"><b>Estimated Unit Cost</b></td>
                        <td colspan="2"><b>Estimated Cost</b></td>
                    </tr>
                    </thead>
                    <tbody class="input_fields_wrap">
                    <?php
                    foreach($item as $row):
                        $total += $row->estimated_cost;
                        $count++;
                    ?>
                    <tr id="{{ $count }}">
                        <input type="hidden" value="{{ $row->id }}" name="pr_id">
                        <td id="border-bottom" class="align-top"><button type="button" value="{{ $count }}" onclick="erase($(this))" class="btn-sm"><small><i class="glyphicon glyphicon-remove"></i></small></button></td>
                        <td id="border-bottom" class="{{ 'issue'.$count }} align-top"><input type="text" name="issue[]" id="{{ 'issue'.$count }}" value="{{ $row->issue }}" class="form-control" onkeyup="trapping()" required><small id="{{ 'E_issue'.$count }}">required!</small></td>
                        <td id="border-bottom" class="{{ 'description'.$count }} align-top" width="40%">
                            <input type="text" name="description[]" id="{{ 'description'.$count }}" value="{{ $row->description }}"  class="form-control" onkeyup="trapping()" required><small id="{{ 'E_description'.$count }}">required!</small>
                            <br><strong><i>Specification(s)</i></strong>
                            <textarea class="textarea" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="specification[]" id="{{ 'specification'.$count }}" onkeyup="trapping()">{{ $row->specification }}</textarea><small id="{{ 'E_specification'.$count }}">required!</small>
                        </td>
                        <td id="border-bottom" class="{{ 'qty'.$count }} align-top"><input type="text" name="qty[]" value="{{ $row->qty }}" id="{{ 'qty'.$count }}" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required><small id="{{ 'E_qty'.$count }}">required!</small></td>
                        <td id="border-bottom" colspan="2" class="{{ 'unit_cost'.$count }} align-top"><input type="text" name="unit_cost[]" id="{{ 'unit_cost'.$count }}" value="{{ $row->unit_cost }}"  class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required><small id="{{ 'E_unit_cost'.$count }}">required!</small></td>
                        <td id="border-bottom" class="{{ 'estimated_cost'.$count }} align-top">
                            <input type="hidden" name="estimated_cost[]" id="{{ 'estimated_cost'.$count }}" value="{{ $row->estimated_cost }}"  class="form-control">
                            <strong style="color:green;">&#x20b1;</strong><strong style="color:green" id="{{ 'e_cost'.$count }}">{{ $row->estimated_cost }} </strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tbody>
                    <tr>
                        <td id="border-top"></td>
                        <td id="border-top"></td>
                        <td id="border-top"></td>
                        <td id="border-top"></td>
                        <td id="border-top" colspan="2"></td>
                        <td id="border-top"><a onclick="add();" href="#">Add new</a></td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6"><b style="float: right">GRAND TOTAL</b></td>
                        <td class="align-top">
                            <input type="hidden" id="count" value="{{ $count }}">
                            <input type="hidden" name="amount" id="amount">
                            <strong style="color: red;">&#x20b1;</strong><strong style="color:red" id="total">{{ $total }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="align">
                            <h3>Certification</h3>
                            This is to certify that dilligent efforts have been exerted to ensure that the price/s indicated above(in relation to the specifications) is/are within the prevailing market price/s.
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="purpose">Purpose:</label>
                        </td>
                        <td colspan="6">
                            <textarea class="form-control" id="purpose" name="purpose" required>{{ $tracking->purpose }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Fund Chargeable To:</label>
                        </td>
                        <td colspan="4">
                            <textarea class="form-control" name="charge_to" required>{{ $tracking->source_fund }}</textarea>
                        </td>
                        <td colspan="2">
                            <label>Cash Advance of:</label>
                            <input type="text" name="cash_advance_of" class="form-control" value="{{ $tracking->cash_advance_of }}" required>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                <table class="letter-head" style="margin-top: 2%">
                    <tr>
                        <td width="15%">
                            <label for="">Signature:</label><br>
                            <label for="">Printed Name:</label><br>
                            <label for="">Designation:</label><br>
                        </td>
                        <td width="23%">
                            <label for="">Requested By</label>
                            <select  class="chosen-select-static form-control" onchange="get_designation($(this),'section',true)" name="requested_by" required>
                                <option value="{{ $requestedBy->id }}">{{ $requestedBy->fullname }}</option>
                                @foreach($headSection as $row)
                                    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                                @endforeach
                                <input id="section_head" class="form-control" readonly>
                            </select>
                        </td>
                        <td>
                            <label for="">Recommending Approval:</label>
                            <select class="chosen-select-static form-control" onchange="get_designation($(this),'division',true);" name="division_head" required>
                                <option value="{{ $recommendingApproval->id }}">{{ $recommendingApproval->fullname }}</option>
                                @foreach($headDivision as $row)
                                    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                                @endforeach
                            </select>
                            <input id="division_head" class="form-control" readonly>
                        </td>
                        <td class="align" width="35%">
                            <label for="">Approved By</label><br>
                            <strong>JAIME S. BERNADAS, MD, MGM, CESO III</strong><br>
                            Director IV
                        </td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <a tabindex="0"
                       class="btn btn-success"
                       role="button"
                       data-html="true"
                       data-toggle="popover"
                       data-trigger="focus"
                       data-placement="top"
                       data-backdrop="static"
                       title="DOMPDF: <input type='radio' value='dompdf' name='pdf' check> FPDF: <input type='radio' value='fpdf' name='pdf' check>"
                       data-content="{{ $paperSize }}"><i class="fa fa-download"></i> Generate-PDF
                    </a>
                    <button class="btn btn-info" type="button" onclick="update_history()">
                    <i class="fa fa-history"></i> History</button>
                    @if($routed < 2 || Session::get('auth')->user_priv)
                        <button class="btn btn-primary" type="submit">
                         <i class="fa fa-edit"></i> Update </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="modal" data-target="#deletePR"><i class="fa fa-trash"></i> Remove</button>
                    @endif
                </div>
            </section>
        </div>
    </div>
</form>
<!-- /.content -->
<div class="clearfix"></div>
<script>
    $("[data-toggle=popover]").popover();
    $('.chosen-select-static').chosen();
    $(".textarea").wysihtml5();
    $(".DatePicker").datepicker();

    var count = $("#count").val();
    var limit = 20;
    trapping(event,false);

    var ok = "";
    function add(){
        event.preventDefault();
        ok = "true";
        var wrapper= $(".input_fields_wrap"); //Fields wrapper

        trapping();
        if(count < limit && ok == "true") {
            count++;
            var url = $("#url").data('link');
            url += "?count=" + count;
            $.get(url, function (result) {
                $(wrapper).append(result);
            });
        }

        CKEDITOR.on('instanceReady', function() {
            console.log($('.cke_contents iframe').contents()[0].body.innerText);
        });

    }

    function trapping(event,flag){
        if(flag)
            key_code(event);
        var estimated_cost = 0;
        var total = 0;
        for(var i=1; i<=count; i++)
        {
            if($("#qty"+i).val() == '' || $("#issue"+i).val() == '' || $("#description"+i).val() == '' || $("#unit_cost"+i).val() == ''){
                ok = "false";
            }
            $("#qty"+i).val() == '' ? ($(".qty"+i).addClass("has-error"),$("#E_qty"+i).show()) :($(".qty"+i).removeClass("has-error"),$("#E_qty"+i).hide()) ;
            $("#issue"+i).val() == '' ? ($(".issue"+i).addClass("has-error"),$("#E_issue"+i).show()) : ($(".issue"+i).removeClass("has-error"),$("#E_issue"+i).hide());
            $("#description"+i).val() == '' ? ($(".description"+i).addClass("has-error"),$("#E_description"+i).show()) : ($(".description"+i).removeClass("has-error"),$("#E_description"+i).hide());
            $("#unit_cost"+i).val() == '' ? ($(".unit_cost"+i).addClass("has-error"),$("#E_unit_cost"+i).show()) : ($(".unit_cost"+i).removeClass("has-error"),$("#E_unit_cost"+i).hide());
            $("#specification"+i).val() == '' ? ($(".specification"+i).addClass("has-error"),$("#E_specification"+i).show()) : ($(".specification"+i).removeClass("has-error"),$("#E_specification"+i).hide());

            var noComma = parseFloat(numeral($("#unit_cost"+i).val()).format('0,0.00').replace(/,/g, ''));
            $("#qty"+i).val() && $("#unit_cost"+i).val() !== '' ? (parseFloat($("#estimated_cost"+i).val($("#qty"+i).val()*noComma))) : $("#estimated_cost"+i).val('');
            $("#qty"+i).val() && $("#unit_cost"+i).val() !== '' ? ($("#e_cost"+i).text(numeral($("#qty"+i).val()*noComma).format('0,0.00')),estimated_cost = $("#estimated_cost"+i).val()) : ($("#e_cost"+i).text(''),estimated_cost = 0);

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

    get_designation("<?php echo $requestedBy->id; ?>","section",false);
    get_designation("<?php echo $recommendingApproval->id; ?>","division",false);
    function get_designation(result,request,event){
        if(event)
            var url = $("#getDesignation").data('link')+'/'+result.val();
        else
            var url = $("#getDesignation").data('link')+'/'+result;

        $.get(url, function(designation){
            if(request == 'section'){
                $("#section_head").val(designation);
            } else {
                $("#division_head").val(designation)
            }
        });
    }

    function erase(result){
        limit++;
        count--;
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