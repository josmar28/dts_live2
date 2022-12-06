<div class="modal" tabindex="-1" role="dialog" id="cancelModal" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal();"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Office Memorandum No. 088 s. 2018</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h3>For strict compliance</h3>
                    <p>Pursuant to National Budget Circular (NBC) No. 573 dated January 3, 2018, <b> all appropriations under FY 2018 GAA</b> shall be valid for release and <b>obligation until December 31, 2018.</b></p>
                    <p><b>All transaction but not limited to</b> ones enumerated below should be submitted, processed and <b>obligated on or before December 28, 2018 last working day.</b>
                    <ul>
                        <li>TEVs;</li>
                        <li>Honorarium;</li>
                        <li>Purchase Order;</li>
                        <li>Notice of Award;</li>
                        <li>Memorandum of Agreement;</li>
                        <li>Remuneration and salaries (last pay of resigned/not renewed JOs);</li>
                        <li>Bill payments (send bill, water, electricity, internet subscription, mobile and telephone);</li>
                        <li>Office rental of PHTs - Bohol, Negros Oriental and Siquijor; and</li>
                        <li>Other legal transactions/claims</li>
                    </ul>
                    <p>This to ensure that every transaction has an allotted budget for payments. Transactions or documents that are forwarded to Budget Section for obligation after the deadline will no longer be accepted.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal();" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-check"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $("#cancelModal").modal('show');
    function closeModal() {
        $("#cancelModal").hide();
    }
</script>