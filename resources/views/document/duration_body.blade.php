<form method="POST" action="{{ asset('document/duration/option') }}">
    {{ csrf_field() }}
    <fieldset>
        <legend><i class="fa fa-hospital-o"></i> Duration Edit</legend>
    </fieldset>
    <input type="hidden" value="@if(isset($data->id)){{ $data->id }}@endif" name="id">
    <div class="form-group">
        <label>Section:</label>
        <select name="section" class="form-control" >
           <option value="">Select</option>
           <?php
            $sections = App\Section::all();
           ?>
             @foreach($sections as $row)
             @if(isset($data->section))
              <option {{ ($data->section == $row->id ? 'selected' : '') }}  value="{{ $row->id }}"> {{ $row->description }}</option>
              @else
              <option value="{{ $row->id }}"> {{ $row->description }}</option>
              @endif
              @endforeach
         </select>
    </div>
    <div class="form-group">
        <label>Document Type:</label>

        <select name="doc_type" class="form-control" >
           <option value="">Select</option>
           <?php
            $doc_type = App\Tracking_Filter::all();
           ?>
            
             @foreach($doc_type as $doc)
             @if(isset($data->doc_type))
            <option {{ ($data->doc_type == $doc->doc_type ? 'selected' : '') }} value="{{ $doc->doc_type }}"> {{ $doc->doc_type }}</option>
            @else
            <option value="{{ $doc->doc_type }}"> {{ $doc->doc_type }}</option>
            @endif
              @endforeach
         </select>
    </div>
    <div class="form-group">
        <label>Document Duration:</label>
        <input type="text" value="@if(isset($data->duration)){{ $data->duration }}@endif" class="form-control" name="duration">
    </div>   
    <hr />
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        @if(isset($data->id))
        <a href="#dur_delete" data-toggle="modal" class="btn btn-danger btn-sm btn-flat dur_delete" data-id ="{{ $data->id }}">
            <i class="fa fa-trash"></i> Remove
        </a>
        @endif
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Save</button>
    </div>
</form>

<script>
    $('.dur_delete').click(function(){
        var id = $(this).data('id');
        $('.dur_id').val(id);
        
    });
</script>

