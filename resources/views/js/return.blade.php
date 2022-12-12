<script>
    $(document).ready(function() {
    var user_priv = <?php echo Session::get('auth')->user_priv;?>;
    var url = "<?php echo url('document/return');?>";
    var section = <?php echo Session::get('auth')->section;?>;
    var _token = "{{ csrf_token() }}";
    
    $.ajax({
                url: url,
                type: 'POST',
                data: {
                    section:section,
                    _token: _token
                },
                success: function(data){
                    console.log(data);
                }
            });
    });

</script>