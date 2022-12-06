<?php
use App\Http\Controllers\DocumentController as Doc;
use App\User as User;
use App\Section;
use App\Http\Controllers\ReleaseController as Rel;
use App\chd12_incidenttype;
use App\Tracking_Releasev2;
use App\Http\Controllers\DocumentController as document;
$id = Session::get('id');

// $type = chd12_incidenttype::where('inctypeid','!=','7')->get();
$type = chd12_incidenttype::where('inctypeid','!=','7')->get();

?>
    <style>
        .trackFontSize{
            font-size: 8pt;
        }
    </style>
    <input type="hidden" value="@if(isset($id)){{ $id }}@endif" name="releaseid">
    <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Type of Incident</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                <select name="inc" class="form-control" style="width: 100%;" required>
                <option value="0">Select a type</option>
                @foreach ($type as $ty)
                   <option value="{{ $ty -> inctypeid }}">{{ $ty -> incident_type }}</option>
                   @endforeach
                </select> 
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Reason of Incident</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                <textarea name="reason" style="width: 100%; height: 100px" class="form-control" required> </textarea>    
                </td>
            </tr>

    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-send"></i> Submit</button>
    </div>



<script>
      $('a[href="#general_form"]').on('click',function(){
        var title = $(this).html();
        var type = $(this).data('type');
        <?php echo 'var url ="'.asset('document/create/').'";';?>
        $('#general_form_title').html(title);
        $.ajax({
            url:url+'/'+type,
            type: 'GET',
            success: function(data){
                $('#general_form_content').html(data);
            }
        })
    });
</script>