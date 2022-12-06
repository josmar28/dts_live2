<style>
    .square {
        border:2px solid #000;
        padding:3px 5px;
    }
    label {
        padding:0px 5px;
    }
    .logo1, .logo2 {
        position: absolute;
    }
    .logo1 {
        left:20px;
    }
    .logo2 {
        right:20px;
    }
</style>
<form action="{{ asset('form/tev') }}" method="POST" id="tevForm">
{{ csrf_field() }}
<input type="hidden" value="{{ Session::get('auth')->id }}" name="prepared_by">
<input type="hidden" value="{{ date('Y-m-d H:i:s') }}" name="prepared_date">
<input type="hidden" value="TEV" name="doc_type">
<br>
<div class="col-lg-12">
<!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#voucher" aria-controls="home" role="tab" data-toggle="tab">Voucher</a></li>
        <li role="presentation"><a href="#appendixA" aria-controls="profile" role="tab" data-toggle="tab">Appendix A</a></li>
        <li role="presentation"><a href="#appendixB" aria-controls="messages" role="tab" data-toggle="tab">Appendix B</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="voucher">
            <div class="modal-body">
                <table class="table table-hover table-form table-bordered">
                    <thead>
                    <tr>
                        <td colspan="4" class="text-center">
                            <div class="logo1">
                                <img src="{{ asset('resources/img/doh.png') }}" width="100px">
                            </div>
                            <div class="logo2">
                                <img src="{{ asset('resources/img/ro7.png') }}" width="100px">
                            </div>
                            Republic of the Philippines<br>
                            <strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO.VII</strong><br>
                            Osme&ntilde;a Boulevard, Cebu City, 6000 Philippines<br>
                            Regional Director's Office Tel. No. (032) 253-6355 Fax No. (032) 254-0109<br>
                            Official Website www.dohro7.gov.ph / Email Address dohro7@gmail.com
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center text-bold text-uppercase">Disbursement Voucher</td>
                    </tr>
                    </thead>
                    <tbody class="has-error">
                    <tr>
                        <td colspan="3" class="text-center">
                            <em>MODE OF PAYMENT</em><br>
                            <label style="cursor: pointer;"><input type="radio" name="mode"> MDS Check </label>
                            <label style="cursor: pointer;"><input type="radio" name="mode"> Commerical Check </label>
                            <label style="cursor: pointer;"><input type="radio" name="mode"> ADA </label>
                            <label style="cursor: pointer;"><input type="radio" name="mode"> Others </label>
                        </td>
                        <td>No.:<br>
                            Date: <strong>{{ date('M d, Y') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Payee/Office: <input type="text" name="payee" class="form-control" placeholder="Enter Payee/Office" required onkeyup="preparedBy($(this))" onchange="preparedBy($(this))"> </td>
                        <td>TIN/Employee No.:</td>
                        <td>OS/BUS NO.:<br>Date:</td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2">Address: <textarea class="form-control" name="address" rows="5" style="resize:none;" required placeholder="Enter Address"></textarea></td>
                        <td colspan="2" class="text-center">Responsibility Center</td>
                    </tr>
                    <tr>
                        <td>Title:</td>
                        <td>Code:</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center">Particulars</td>
                        <td class="text-center">Amount</td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea class="form-control" rows="10" style="resize:none;" required placeholder="Enter Particulars" name="particulars"></textarea></td>
                        <td><input type="text" class="form-control" placeholder="Enter Amount" onkeyup="putAmount($(this))" required name="amount"> </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right text-bold">Amount Due <i class="fa fa-arrow-right"></i> </td>
                        <td class="text-center text-bold"><span class="amount">0</span> </td>
                    </tr>
                    </tbody>
                    <tfoot class="has-error">
                    <tr>
                        <td colspan="2" style="vertical-align: top;">

                            <table cellspacing="10">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <span class="square">A</span> Certified:
                                    </td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td>
                                        <label style="cursor: pointer;"><input type="radio" name="certified"> Supporting documents complete and proper </label><br>
                                        <label style="cursor: pointer;"><input type="radio" name="certified"> Cash Available </label><br>
                                        <label style="cursor: pointer;"><input type="radio" name="certified"> Subject to ADA (where applicable) </label>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table width="100%">
                                <tr>
                                    <td width="30%">Signature:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;"></td>
                                </tr>
                                <tr>
                                    <td width="30%">Printed Name:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control" placeholder="Full Name" name="fullNameA">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Position:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control" placeholder="Position" name="positionA">
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="text-center" style="vertical-align: top"><small>(Head, Accounting Unit/Authorized Representative)</small></td>
                                </tr>
                                <tr>
                                    <td width="30%">Date:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;"></td>
                                </tr>
                            </table>
                        </td>
                        <td colspan="2" style="vertical-align: top;">
                            <span class="square">B</span> Approved for Payment:
                            <br><br><br><br><br>
                            <table width="100%">
                                <tr>
                                    <td width="30%">Signature:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;"></td>
                                </tr>
                                <tr>
                                    <td width="30%">Printed Name:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control" placeholder="Full Name" name="fullNameB">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Position:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control" placeholder="Position" name="positionB">
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="text-center" style="vertical-align: top"><small>(Agency Head/ Authorized Representative)</small></td>
                                </tr>
                                <tr>
                                    <td width="30%">Date:</td>
                                    <td width="70%" style="border-bottom: 1px solid #000;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="vertical-align: top;">
                            <span class="square">C</span> Received Payment:
                            <br><br>
                            <table width="100%">
                                <tr>
                                    <td width="20%">Signature:</td>
                                    <td width="30%" style="border-bottom: 1px solid #000;"></td>
                                    <td width="15%">Date:</td>
                                    <td width="35%" style="border-bottom: 1px solid #000;"><strong>{{ date('M d, Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>Printed Name:</td>
                                    <td colspan="2"><input type="text" class="form-control" placeholder="Full Name" name="fullNameC"></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="2"><input type="text" class="form-control" placeholder="Position" name="positionC"  onkeyup="position($(this))" onchange="position($(this))"></td>
                                </tr>
                            </table>
                        </td>
                        <td colspan="2" style="vertical-align: top;">
                            <span class="square">D</span> Journal Entry Voucher
                            <br><br>
                            <table width="100%">
                                <tr>
                                    <td width="30%">Check/ADA No.:</td>
                                    <td colspan="3" width="69%" style="border-bottom: 1px solid #000;"></td>
                                </tr>
                                <tr>
                                    <td width="30%">Date:</td>
                                    <td width="30%" style="border-bottom: 1px solid #000;"></td>
                                    <td width="15%">No.:</td>
                                    <td width="25%" style="border-bottom: 1px solid #000;"></td>
                                </tr>
                                <tr>
                                    <td>Bank Name:</td>
                                    <td style="border-bottom: 1px solid #000;"></td>
                                    <td>Date:</td>
                                    <td style="border-bottom: 1px solid #000;"></td>
                                </tr>
                            </table>
                            <br>
                            OR NO./ other relevant document <br>issued:
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                <button type="button" class="btn btn-success" data-target="#appendixA" data-toggle="tab"><i class="fa fa-arrow-right"></i> Next</button>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="appendixA">
            <div class="modal-body">
                <table class="table table-bordered has-error">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-center">Republic of the Philippines<br>Department of Health - Regional Office VII<br>Cebu City</th>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-center text-bold" style="text-decoration: underline;">A P P E N D I X&nbsp;&nbsp;&nbsp;&nbsp;A</td>
                        </tr>
                        <tr>
                            <td colspan="5">No.: ______________</td>
                            <td colspan="5" class="text-right">Date : <strong><?php echo date('M d, Y');?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                NAME:<input type="text" name="fullNameD" class="form-control" placeholder="Full Name" required>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5">POSITION: <input type="text" name="positionD" class="form-control" placeholder="Position" required></td>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="10">OFFICIAL STATION: <input type="text" name="stationAddress" class="form-control" placeholder="Station Address" required></td>
                        </tr>
                        <tr>
                            <td colspan="10">PURPOSE OF TRAVEL: <input type="text" name="travelPurpose" class="form-control" placeholder="Purpose of Travel" required></td>
                        </tr>
                        <tr class="text-center text-bold">
                            <td rowspan="2" style="vertical-align: middle;">DATE</td>
                            <td colspan="2" rowspan="2" style="vertical-align: middle;">PLACE TO BE VISITED</td>
                            <td colspan="2" style="vertical-align: middle;">TIME</td>
                            <td rowspan="2" style="vertical-align: middle;">MEANS<br>OF<br>TRANS.</td>
                            <td colspan="3" style="vertical-align: middle;">ALLOWABLE EXPENSES</td>
                            <td rowspan="2" style="vertical-align: middle;">TOTAL</td>
                        </tr>
                        <tr class="text-center text-bold">
                            <td>Departure</td>
                            <td>&nbsp;&nbsp;&nbsp;Arrival&nbsp;&nbsp;&nbsp;</td>
                            <td>Trans.<br>Allowance</td>
                            <td>Daily<br>Allowance</td>
                            <td>Per<br>Diems</td>
                        </tr>
                    </thead>
                    <tbody id="append">
                        <tr>
                            <td><input type="date" name="date[]" class="form-control"></td>
                            <td colspan="2"><input type="text" name="visited[]" class="form-control"></td>
                            <td>
                                <select name="hourA[]" class="form-control append">
                                    @for($i=0;$i<=12;$i++)
                                        <option>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select name="minA[]" class="form-control">
                                    @for($i=0;$i<60;$i++)
                                        <option>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select name="ampmA[]" class="form-control">
                                    <option>AM</option>
                                    <option>PM</option>
                                </select>
                            </td>
                            <td>
                                <select name="hourB[]" class="form-control">
                                    @for($i=1;$i<=12;$i++)
                                        <option>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select name="minB[]" class="form-control">
                                    @for($i=0;$i<60;$i++)
                                        <option>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select name="ampmB[]" class="form-control">
                                    <option>AM</option>
                                    <option>PM</option>
                                </select>
                            </td>
                            <td><input type="text" name="trans[]" class="form-control"></td>
                            <td><input type="text" name="transAllow[]" class="form-control"></td>
                            <td><input type="text" name="dailyAllow[]" class="form-control"></td>
                            <td><input type="text" name="perDiem[]" class="form-control"></td>
                            <td><input type="text" name="total[]" class="form-control" onkeyup="subTotal()"></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" class="text-center"><a href="#" onclick="append()" class="btn btn-info btn-sm"><i class="fa fa-file"></i> New Item</a> </td>
                        </tr>
                        <tr>
                            <td colspan="9" class="text-center text-bold">T O T A L</td>
                            <td class="text-bold grandTotal">0</td>
                        </tr>
                        <tr>
                            <td colspan="10"></td>
                        </tr>
                        <tr>
                            <td colspan="5" rowspan="6">I hereby certify that(a) I have reviewed<br>the foregoing itinerary (b) the travel is<br>necessary to the service (c) the period<br>covered is reasonable (d) the expenses<br>claimed are proper:</td>
                            <td colspan="5">Prepared By:</td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center">
                                <input type="text" class="form-control" name="fullNameE" placeholder="Full Name" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center">Employee</td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5">Approved:</td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center"><input type="text" class="form-control" name="fullNameF" required placeholder="Full Name"></td>
                            <td colspan="5" class="text-center"><input type="text" class="form-control" name="fullNameG" required placeholder="Full Name"></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center"><input type="text" class="form-control" name="positionF" required value="Supervisor" placeholder="Position"></td>
                            <td colspan="5" class="text-center"><input type="text" class="form-control" name="positionG" required value="OIC - Director III" placeholder="Position"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-target="#voucher" data-toggle="tab"><i class="fa fa-arrow-left"></i> Previous</button>
                <button type="button" class="btn btn-success" data-target="#appendixB" data-toggle="tab"><i class="fa fa-arrow-right"></i> Next</button>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="appendixB">
            <div class="modal-body">
                <table class="table table-bordered has-error">
                    <tr>
                        <th colspan="3" class="text-center" style="text-decoration: underline;">A P P E N D I X   B</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center"><font class="text-bold" style="text-decoration: underline">&nbsp;&nbsp;&nbsp;JAIME S. BERNADAS&nbsp;&nbsp;&nbsp;</font><br>Agency Head<br></td>
                        <td></td>
                        <td class="text-center"><font class="text-bold" style="text-decoration: underline">&nbsp;&nbsp;&nbsp;DOH-REGIONAL OFFICE VII&nbsp;&nbsp;&nbsp;</font><br>Station</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center text-bold" style="text-decoration: underline;">&nbsp;&nbsp;&nbsp;Director IV&nbsp;&nbsp;&nbsp;</td>
                        <td></td>
                        <td class="text-center text-bold"><font  style="text-decoration: underline;">&nbsp;&nbsp;&nbsp;20-12-2016&nbsp;&nbsp;&nbsp;</font><br>Date</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                        <table width="100%">
                            <tr>
                                <td width="50px"></td>
                                <td>I certify that I have completed the travel authorized the itenerary of Travel No. dated ____________</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">( x )</td>
                                <td>Strictly in accordance with the approved itinerary.</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">( &nbsp; )</td>
                                <td>Cut short as explained below.</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">( &nbsp; )</td>
                                <td>Excess Payment in the amount of _____________________________ was refunded on O.R. No. ___________ dated ______________</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">( &nbsp; )</td>
                                <td>Extended as explained below Additional Itinerary was submitted. Explanations or          Justifications ____________________________________________________          _______________________________________________________________</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">( x )</td>
                                <td>Evidence of Travel attached hereto ____________ Certificate of appearance, boat ticket, bus ticket boat ticket, office order,RER's _________________________________________________.</td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">Respectfully Submitted:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="text-center"><input type="text" class="form-control" name="fullNameH" required placeholder="Prepared By Full Name"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="text-center">Employee</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center">On evidence and information,of which I have knowledge was actually undertaken</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="text-center"><input type="text" class="form-control" name="fullNameI" required placeholder="Supervisor Full Name"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="text-center">Supervisor</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-target="#appendixA" data-toggle="tab"><i class="fa fa-arrow-left"></i> Previous</button>
                <button type="submit" class="btn btn-success" onclick="$('form').attr('target','');"><i class="fa fa-send"></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

</form>
