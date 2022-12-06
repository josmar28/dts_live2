<?php
use App\Http\Controllers\DocumentController as Doc;
use App\User as User;
use App\Section;
use App\Http\Controllers\ReleaseController as Rel;
use App\chd12_incidenttype;
use App\Tracking_Releasev2;
use App\Http\Controllers\DocumentController as document;

        
$type = chd12_incidenttype::all();
$id = Session::get('id');   
$data1 = DB::table('chd12_incidentreport')
->select('chd12_incidentreport.*','chd12_incidenttype.incident_type','chd12_incidenttype.inctypeid')
->leftJoin('chd12_incidenttype', 'chd12_incidenttype.inctypeid', '=', 'chd12_incidentreport.incident_typeid')
->where('chd12_incidentreport.incid',$id)->first();

?>

    <style>
        .trackFontSize{
            font-size: 8pt;
        }
    </style>
    <form action="{{ asset('chd12report/insertEdit') }}" method="POST" class="form-submit">
    {{ csrf_field() }}
        <input type="hidden" value="<?php echo $id;?>" name="releaseid">
    <div class="modal-body">
        <table class="table table-hover table-striped">        
         <tr>
                <td class="col-sm-3"><label>Type of Incident</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="inc" class="chosen-select form-control"  style="width: 100%;" required>
                    <option value="{{ $data1 -> inctypeid }}">{{ $data1 -> incident_type }}</option>
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
                <textarea name="reason"  class="form-control"  style="width: 100%; height: 100px" required>{{ $data -> reason }}</textarea>
                </td>

         </tr>
          
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>


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