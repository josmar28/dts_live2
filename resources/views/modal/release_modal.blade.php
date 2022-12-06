<div class="modal fade" tabindex="-1" role="dialog" id="releaseTo" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ asset('document/release') }}" name="destinationForm" class="form-submit">
                <div class="modal-body">
                    <h4 class="text-success"><i class="fa fa-send"></i> Select Destination</h4>
                    <hr />
                    {{ csrf_field() }}
                    <input type="hidden" name="route_no" id="route_no">
                    <input type="hidden" name="op" id="op" value="0">
                    <input type="hidden" name="currentID" id="currentID" value="0">
                    <div class="form-group">
                        <label>Division</label>
                        <select name="division" class="chosen-select filter-division" required>
                            <option value="">Select division...</option>
                            <?php $division = \App\Division::where('description','!=','Default')->orderBy('description','asc')->get(); ?>
                            @foreach($division as $div)
                                <option value="{{ $div->id }}">{{ $div->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <select name="section" class="chosen-select filter_section" required>
                            <option value="">Select section...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="5" style="resize: vertical;" placeholder="Please enter your remark(s) of return..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-success btn-submit" onclick="checkDestinationForm()"><i class="fa fa-send"></i> Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="returnRemark" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-success"><i class="fa fa-book"></i> Remarks</h4>
                <hr />
                <textarea name="remarks" class="form-control" id="return_remarks" rows="7" style="resize: vertical;" placeholder="Please enter your remark(s) of return..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-success confirmReturn" data-dismiss="modal"><i class="fa fa-check"></i> Return</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="transmittal_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body transmittal_body">
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="acceptModal" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-success"><i class="fa fa-book"></i> Remarks</h4>
                <hr />
                <textarea name="remarks" class="form-control" id="accept_remarks" rows="7" style="resize: vertical;" placeholder="Please enter your remark(s) of accept..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-success confirmAccept" data-dismiss="modal"><i class="fa fa-check"></i> Accept</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="removeIncoming" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-info"><i class="fa fa-question-circle"></i> Confirmation</h4>
                <hr />
                <div class="alert alert-danger">
                    Are you sure you want to remove this incoming document?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                <button type="button" class="btn btn-success confirmRemoveIncoming" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="removeOutgoing" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-info"><i class="fa fa-question-circle"></i> Confirmation</h4>
                <hr />
                <div class="alert alert-danger">
                    Are you sure you want to remove this outgoing document?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                <button type="button" class="btn btn-success confirmRemoveOutgoing" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="removeModal" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-success"><i class="fa fa-question-circle"></i> Confirmation</h4>
                <hr />
                <div class="alert alert-warning">
                    Are you sure you want to end this cycle?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                <button type="button" class="btn btn-success confirmRemove" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="cancelModal" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-success"><i class="fa fa-question-circle"></i> Confirmation</h4>
                <hr />
                <div class="alert alert-warning">
                    Are you sure you want to cancel?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                <button type="button" class="btn btn-success confirmCancel" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->