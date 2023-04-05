<div class="modal fade" tabindex="-1" role="dialog" id="newModal" style="margin-top: 30px;z-index: 99999 ">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h3 style="font-weight: bold" class="text-success">WHAT'S NEW?</h3>
                <?php
                $dateNow = date('Y-m-d');
                ?>
                @if($dateNow==='2019-07-30')
                    <div class="alert alert-info">
                        <p class="text-info" style="font-size:1.3em;text-align: center;">
                            <strong>There will be a server maintenance TODAY (July 30, 2019) at 1:15PM to 02:00PM. Server optimization!</strong>
                        </p>
                    </div>
                @endif
                @if($dateNow >= '2020-07-01' && $dateNow <= '2021-12-30')
                    <div class="">
                            <span class="text-info" style="font-size:1.1em;">
                                <strong>DOH-CHD XII Document Tracking System(DTS)</strong><br>
                                <ol>
                                    <li>
                                        MULTIPLE RELEASE <small class="badge bg-red" style="font-size: 6pt;"> New</small>
                                    </li>
                                    <li>
                                        INCIDENT LOGS <small class="badge bg-red" style="font-size: 6pt;"> New</small>
                                    </li>
                                    <li>
                                        Generate Transmittal on My Documents, Personal logs and Section logs <small class="badge bg-red" style="font-size: 6pt;"> New</small>
                                    </li>
                                </ol>
                            </span>
                    </div>
                @endif
                @if($dateNow >= '2019-07-01' && $dateNow <= '2019-12-30')
                    <br><div class="text-info">
                        <strong >ANNOUNCEMENT</strong>
                        <blockquote style="font-size:1.1em;">
                            Good day everyone!
                            <br><br>
                            Please be informed that there will be a new URL/Link for the E-Referral from 203.177.67.126/doh/referral to 124.6.144.166/doh/referral
                            <br><br>
                            The said new URL/Link will be accessible on AUGUST 2, 2020 at 3PM.
                            And there will be a downtime on AUGUST 2, 2020 at 1PM to 3PM for the configuration of our new URL/Link.
                            <br><br>
                            Please be guided accordingly.
                            <br><br>
                            Thank you very much and keep safe.
                        </blockquote>
                    </div>
                @endif
                @if($dateNow >= '2019-11-19' && $dateNow <= '2019-11-30')
                    <div class="alert alert-info">
                        <span class="text-info" style="font-size:1.1em;">
                            <strong><i class="fa fa-info"></i> Version 2.1 was successfully launch</strong><br>
                            <ol type="I" style="color: #31708f;font-size: 10pt;margin-top: 10px;">
                                <li><i><b>Editable Patient</b></i> - Allowing the user to edit misspelled / typo informations</li>
                                <li><i><b>Facility Dropdown</b></i> - Allowing the dropdown be search by keyword</li>
                                <li><i><b>Outgoing Referral Report</b></i> - Adding the department to be filter</li>
                                <li><i><b>Login Lifetime</b></i> - Session will expire in 30 minutes</li>
                                <li><i><b>Input Date Range</b></i> - Filter date range UI interface improve</li>
                                <li><i><b>Incoming Page</b></i> - UI interface improve and fixed bugs</li>
                            </ol>
                        </span>
                    </div>
                @endif
                <div class="text-warning">
                    <span style="font-size:1.1em;color: #8a6d3b;">
                        <strong><i class="fa fa-wifi"></i>DOH-CHD XII Document Tracking System(DTS) URL</strong><br>
                        <ol>
                            <li>Main domain: http://dtrakro12.doh.gov.ph</li>
                            <li>Local IP: http://192.168.1.176</li>
                            <li>Public IP: http://122.53.156.201</li>
                        </ol>
                    </span>
                </div>
                <div >
                    <div class="text-success">
                        <i class="fa fa-phone-square"></i> For further assistance, please message these following:
                        <ol type="I" style="color: #2f8030">
                            <li>Technical</li>
                            <ol type="A">
                                <li >System Error</li>
                                <ol>
                                    <li>Josmar V. Del Poso – 09167063036</li>
                                    <li>ARON JOSEPH B BRA - 09515359406</li>
                                </ol>
                                <li >Server - The request URL not found</li>
                                <ol>
                                    <li>Garizaldy B. Epistola - 09338161374</li>
                                    <li>John Philip M. Nacional - 09169229872</li>
                                    <li>Kenny S. Marañon - 09158742458</li>
                                </ol>
                                <li >System Implementation/Training</li>
                                <ol>
                                    <li>MICHAEL R. BIRUAR - 09264029988</li>
                                </ol>
                                <li >
                                    Register Account and Forget Password
                                </li>
                                <ol>
                                    <li>MICHAEL R. BIRUAR - 09264029988</li>
                                    <li>Josmar V. Del Poso – 09167063036</li>
                                    <li>ARON JOSEPH B BRA - 09515359406</li>
                                </ol>
                            </ol>
                        </ol>
                    </div>
                </div>
                <div class="text-danger">
                    <div style="font-size:1.1em;">
                        <!-- <i class="fa fa-phone-square"></i> 711 DOH CVCHD HealthLine <strong>(032)411-6900</strong> -->
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $('#newModal').modal('show');
</script>