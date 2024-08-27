<script>
    $('#service-delete-form').on('submit',function(e){
    var url = "{{url('/delete-service-type')}}";
    e.preventDefault();
    $(".loading").show();
    $('#service-delete-form').ajaxSubmit({
        url:  url,
        type: "POST",
        success: function(data){
            setTimeout(function(){
                window.location.reload(false);
            },500);
        },
        error: function (data) {
            $.toast({
                heading: 'Error',
                text: 'Something went wrong, Please contact administrator',
                showHideTransition: 'fade',
                position: 'top-center',
                icon: 'error'
            })
        },
    });
});

    $('#add-service-modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })


    $('.btn_delete').on('click',function() {
    var id = $(this).data('id');
    $("#id").val(id);
});

    $('.btn_view').on('click',function() {
    var id = $(this).data('id');
    var url = "{{url('service-type-info')}}";
    $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
            id: id,
            _token : "<?php echo csrf_token(); ?>"
        },
        success : function(data){
        console.log(data);
            $('.description').val(data.description);
            $('.days').val(data.days);
            $('.hours').val(data.hours);
            $('.minutes').val(data.minutes);
            $('.id').val(data.id);
        },
        error: function (data) {
           
        },
    });
});
    $('body').on('submit', '#add-service-form', function(e) {
        e.preventDefault();
    	$('.loading').show();
    	var url = "{{url('/submit-add-service')}}";
        $(this).ajaxSubmit({
            url: url,
            type: 'POST',
            success: function(data){
                $(".loading").hide();
                setTimeout(function(){
                    window.location.reload(false);
                },500);
            },
            error: function(){
                $('#serverModal').modal();
            }
        });
    });

    @if(Session::get('service_add'))
            Lobibox.notify('success', {
            title: "",
            msg: "Service Successfully Created",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("service_add",false);
        ?>
    @endif

    @if(Session::get('service_update'))
            Lobibox.notify('success', {
            title: "",
            msg: "Service Successfully Updated",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("service_update",false);
        ?>
    @endif

    @if(Session::get('service_delete'))
            Lobibox.notify('warning', {
            title: "",
            msg: "Service Successfully Deleted",
            size: 'mini',
            rounded: true
            });
        <?php
            Session::put("service_delete",false);
        ?>
    @endif
</script>