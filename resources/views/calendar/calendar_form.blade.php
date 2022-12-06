<?php
    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    function random_color() {
        return random_color_part() . random_color_part() . random_color_part();
    }
?>
<form action="{{ asset('calendar_save') }}" method="POST">
    <input type="hidden" name="background_color" value="{{ random_color() }}">
    <input type="hidden" name="border_color" value="{{ random_color() }}">
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class=""><label>Event</label></td>
                <td>:</td>
                <td><textarea class="form-control" id="addtionalinfo" name="title" rows="2" style="resize:none;" required></textarea></td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Start</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input name="start" class="form-control datepickercalendar" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>End</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input name="end" class="form-control datepickercalendar" required>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" onclick="PO_reload();" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
<script src="{{ asset('resources/plugin/datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    var datePicker = $('body').find('.datepicker');
    //Date picker
    $('.datepickercalendar').datepicker({
        autoclose: true
    });
</script>