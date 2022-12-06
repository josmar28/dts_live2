<form method="POST" action="{{ asset('document/filter_body') }}">
    {{ csrf_field() }}
    <fieldset>
        <legend><i class="fa fa-hospital-o"></i>Document Details</legend>
    </fieldset>
    <input type="hidden" value="@if(isset($data->id)){{ $data->id }}@endif" name="id">
    <input type="hidden" value="0" name="void">
    <div class="form-group">
        <label>Document Code:</label>
        <input type="text" class="form-control" value="@if(isset($data->doc_type)){{ $data->doc_type }}@endif" autofocus name="doc_type" required>
    </div>
    <div class="form-group">
        <label>Document Description:</label>
        <input type="text" class="form-control" value="@if(isset($data->doc_description)){{ $data->doc_description }}@endif" name="doc_description">
    </div>
 
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        @if(isset($data->id))
        <a href="#filter_delete" data-dismiss="modal" data-toggle="modal" class="btn btn-danger btn-sm btn-flat" onclick="docDelete('<?php echo $data->doc_type; ?>')">
            <i class="fa fa-trash"></i> Remove
        </a>
        @endif
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Submit</button>
    </div>
</form>

<script>
    $(".select2").select2({ width: '100%' });

    $('.select_province').on('change',function(){
        $('.loading').show();
        var province_id = $(this).val();
        var url = "{{ url('location/muncity/') }}";
        $.ajax({
            url: url+'/'+province_id,
            type: 'GET',
            success: function(data){
                console.log(data);
                $('.loading').hide();
                $('.select_muncity').empty()
                    .append($('<option>', {
                        value: '',
                        text : 'Select Municipality'
                    }));
                $('.select_barangay').empty()
                    .append($('<option>', {
                        value: '',
                        text : 'Select Barangay'
                    }));
                jQuery.each(data, function(i,val){
                    $('.select_muncity').append($('<option>', {
                        value: val.id,
                        text : val.description
                    }));
                });
            },
            error: function(){
                $('#serverModal').modal();
            }
        });

    });

    $('.select_muncity').on('change',function(){
        $('.loading').show();
        var province_id = $(".select_province").val();
        var muncity_id = $(this).val();
        var url = "{{ url('location/barangay/') }}";
        $.ajax({
            url: url+'/'+province_id+'/'+muncity_id,
            type: 'GET',
            success: function(data){
                $('.loading').hide();
                $('.select_barangay').empty()
                    .append($('<option>', {
                        value: '',
                        text : 'Select Barangay'
                    }));
                jQuery.each(data, function(i,val){
                    $('.select_barangay').append($('<option>', {
                        value: val.id,
                        text : val.description
                    }));
                });
            },
            error: function(){
                $('#serverModal').modal();
            }
        });

    });
</script>


