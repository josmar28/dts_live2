<form action="{{ asset('/edit/designation') }}" method="POST" id="">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ $d->id }}" />
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Designation</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" name="designation" value="{{ $d->description }}" class="form-control" required></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i>Update</button>
    </div>
</form>
