    $(function(){
    $('input').attr('autocomplete', 'off');
    var url = window.location.pathname;
    var host = window.location.hostname;
    var filename = window.location.href;
    //$('.sidebar-menu li a[href="'+filename+'"]').parent('li').addClass('active');
    $('.navbar-nav li a[href="'+filename+'"]').parent('li').addClass('active');
    //console.log('.navbar-nav li a[href="'+filename+'"]').addClass('active');

    //tracking history of the document
    $("a[href='#track']").on('click',function(){
        $('.track_history').html(loadingState);
        var route_no = $(this).data('route');
        var url = $(this).data('link');
        $('#track_route_no').val('Loading...');
        setTimeout(function(){
            $('#track_route_no').val(route_no);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    console.log(url);
                    $('.track_history').html(data);
                }
            });
        },1000);
        
    });


    //document information
    $("a[href='#document_info']").on('click',function(){
        var route_no = $(this).data('route');
        $('.modal_content').html(loadingState);
        $('.modal-title').html('Route #: '+route_no);
        var url = $(this).data('link');
        setTimeout(function(){
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.modal_content').html(data);
                    $('#reservation').daterangepicker();
                    var datePicker = $('body').find('.datepicker');
                    $('input').attr('autocomplete', 'off');
                }
            });
        },1000);

    });
    //document information 2
    $("a[href='#document_info_pending']").on('click',function(){
        var route_no = $(this).data('route');
        $('.modal_content').html(loadingState);
        $('.modal-title').html('Route #: '+route_no);
        var url = $(this).data('link');
        console.log(url);
        setTimeout(function(){
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.modal_content').html(data);
                    $('#reservation').daterangepicker();
                    var datePicker = $('body').find('.datepicker');
                    $('input').attr('autocomplete', 'off');
                }
            });
        },1000);

    });
        //remove pending documents
        $("a[href='#remove_pending']").on('click',function(){
            var button = $(this);
            $('#nametoDelete').html('this document');
            $('#confirmation').modal('toggle');
            $('#confirm').on('click',function(){
                var id = button.data('id');
                $('.loading').show();
                var url = button.data('link');
                setTimeout(function(){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            $('.table-'+id).fadeOut();
                            $('.loading').hide();
                            window.location.reload();
                        }
                    });
                },500);
            });
        });
    //Get forms
    $('a[href="#document_form"]').on('click',function(){
        $('.modal_content').html(loadingState);
        $('.modal-title').html($(this).html());
        var url = $(this).data('link');
        setTimeout(function() {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.modal_content').html(data);
                    $('#reservation').daterangepicker();
                    var datePicker = $('body').find('.datepicker');
                    //Date picker
                    $('.datepickercalendar').datepicker({
                        autoclose: true
                    });
                    $('input').attr('autocomplete', 'off');
                    $('.chosen-select').chosen();
                }
            });
        },1000);
    });

});

function acceptNumber($this){
    $this.val($this.val().replace(/[^\d+(\.\.]/g, ''));
}
function PRC_reload(){
    if($("#itemdescription").val() && $("#amount").val() && $("#requestedby").val() && $("#chargeto").val() && $("#purpose").val() != ''){
        setTimeout(function () { window.location.reload(); }, 10);
    }
}
function PRR_reload(){
    if($("#pr_no").val() && $("#amount").val() && $("#requestedby").val() && $("#chargeto").val() && $("#purpose").val() != ''){
        setTimeout(function () { window.location.reload(); }, 10);
    }
}
function PO_reload(){
    if($("#pr_no").val() && $("#po_date").val() && $("#pr_no").val() && $("#pr_date").val() && $("#supplier").val() != ''){
        setTimeout(function () { window.location.reload(); }, 10);
    }
}

function trackDocument(){
    var route_no = $('#track_route_no2').val();
    var url = $('#trackForm').attr('action')+'/'+route_no;
    $('.track_history').html(loadingState);
    if(route_no.length > 0){
        setTimeout(function(){
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.track_history').html(data);
                    $('.btn-print').removeClass('hide');
                }
            });
        },1000);
    }else{
        setTimeout(function(){
            $('.track_history').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Please enter route number!</div>');
            $('.btn-print').addClass('hide');
        },1000);
    }
    return false;
}

$('a[href="#new"]').on('click',function(e){
    $('#document_form').modal('show');
    $('.modal_content').html(loadingState);
    $('.modal-title').html($(this).html());
    var url = $(this).data('link');
    setTimeout(function() {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('.modal_content').html(data);
                $('#create').attr('action', url);
                $('input').attr('autocomplete', 'off');
            }
        });
    },1000);
});

$('a[href="#remove_designation"]').on('click', function (event) {
    var data = {
        "id" : $(this).data('id')
    };
    var url = $(this).data('link');
    if(confirm("Delete designation ?") == true){
        $.get(url,data,function(response){
            var status = JSON.parse(response);
            if(status.status == "ok") {
                alert("Designation deleted.");
                var url = $('#url').data('link');
                window.location.href = url;
            }
        });
    }
});
$('a[href="#edit_designation"]').on('click',function(event){
    $('#designation_edit').modal('show');
    $('.modal_content').html(loadingState);
    $('.modal-title').html($(this).html());
    var url = $(this).data('link');
    var data = {
        "id" : $(this).data('id')
    };
    $.get(url,data,function(response){
        $('.modal_content').html(response);
        $('#create').attr('action', url);
        $('input').attr('autocomplete', 'off');
    });
});

function deleteSection(result) {
    $("#nametoDelete").html(result.val());
    $('#confirm').on('click', function () {
        $('.loading').show();
        var url = result.data('link');
        setTimeout(function () {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: $(this).serialize(),
                success: function (resultData) {
                    $('.loading').hide();
                    window.location.reload();
                }
            });
        }, 500);
    });
}

function deleteDivision(result) {
    $("#nametoDelete").html(result.val());
    $('#confirm').on('click', function () {
        $('.loading').show();
        var url = result.data('link');
        setTimeout(function () {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: $(this).serialize(),
                success: function (resultData) {
                    $('.loading').hide();
                    window.location.reload();
                }
            });
        }, 500);
    });
}

$('a[href="#user"]').on('click', function(event){
    $('#document_form').modal('show');
    $('.modal_content').html(loadingState);
    $('.modal-title').html($(this).html());
    var data = {
        "id" : $(this).data('id')
    };
    var url = $(this).data('link');
    $.get(url,data, function(response){
        $('.modal_content').html(response);
        $('#create').attr('action', url);
        $('input').attr('autocomplete', 'off');
    });
});

function loadDivision(el){
    var url = $(el).data('link');
    var id = {
        "id" : $(el).val()
    };
    var next = $(el).parent().parent().parent();
    $.get(url,id,function(response){
        $(".section_append").html(response);
    });
}
function del_user(el) {
    var url = $(el).data('link');
    var data = {
        "id" : $(el).data('id'),
        "_token" : $('#token').data('token')
    };

    $('#confirmation').modal('show');
    $('#confirm').click(function(){
        $.post(url,data, function (response) {

            alert(response)
            // if(JSON.parse(response).status == "ok") {
            //     window.location.reload();
            // }
        });
    });
}
function searchSection(result){
    var url = result.data('link');
    var save = {
        "search" : $("#search").val(),
        "_token" : $("#token").data("token")
    };
    $.post(url,save,function(){
        window.location.href = url;
    });
}
function searchDivision(result){
    var url = result.data('link');
    var save = {
        "search" : $("#search").val(),
        "_token" : $("#token").data("token")
    };
    $.post(url,save,function(){
        window.location.href = url;
    });
}


function delete_designation(el) {
   var url = $('#delete').data('link');
   var data = {
        "id" : $(el).data('id'),
       "_token" : $('#token').data('token')
   };
   $('#confirmation').modal('show');
   $('#confirm').click(function(){
       $.post(url,data,function(response){
            if(JSON.parse(response).status == "ok") {
                window.location.reload();
            }
       });
   });
}

function edit_designation(el) {
    var url = $('#edit').data('link');
    var data = {
        "id" : $(el).data('id'),
        "_token" : $('#token').data('token')
    };
    $('#document_form').modal('show');
    $('.modal_content').html(loadingState);
    $('.modal-title').html($(this).html());

    $.get(url,data,function(response){
        $('.modal_content').html(response);
        $('#create').attr('action', url);
        $('input').attr('autocomplete', 'off');
    });
}

function checkDescription(description){
    var url = $(description).data('link');
    var data = {
        "description" : $(description).val()
    }
    $.get(url,data,function(response){
        var res = JSON.parse(response);
        if(res.status == "ok") {
            $(description).next().removeClass('hidden');
            $('#sectionSubmit').prop('disabled',true);
        }
        if(res.status == "false") {
            $(description).next().addClass('hidden');
            $('#sectionSubmit').prop('disabled',false);
        }
    });
}

function checkDescriptionUpdate(description){
    var url = $(description).data('link');
    var data = {
        "description" : $(description).val()
    }
    $.get(url,data,function(response){
        var res = JSON.parse(response);
        if(res.status == "ok" && $("#uniqueDescription").val() != $(description).val() ) {
            $(description).next().removeClass('hidden');
            $('#sectionSubmit').prop('disabled', true);
        }
        if(res.status == "false" || $("#uniqueDescription").val() == $(description).val() ) {
            $(description).next().addClass('hidden');
            $('#sectionSubmit').prop('disabled',false);
        }
    });
}

function divisionValidate(){
    if($("#division").val() == ""){
        $('#form').attr('onsubmit','return false;');
        $("#divisionBorder").css({'border': '2px solid red'});
    } else {
        $("#divisionBorder").removeAttr("style");
    }
    if($("#head").val() == ""){
        $('#form').attr('onsubmit','return false;');
        $("#headBorder").css({'border': '2px solid red'});
    } else {
        $("#headBorder").removeAttr("style");
    }
    if($("#division").val() && $("#description").val() && $("#head").val() != ""){
        $('#form').attr('onsubmit','return true;');
    }
}

function isEmpty(val){
    if(val == null ) return false;
    return (val.length <= 0 || val == "" || val == undefined);
}

//logs
    $('.filter-division').show();
    $('.filter-division').on('change',function(){
        checkDestinationForm();
        var id = $(this).val();
        var url = $("#getsection").val();
        $('.loading').show();
        $('.filter_section').html('<option value="">Select section...</option>');
        $.ajax({
            url: url+'/'+id,
            type: "GET",
            success: function(sections){
                jQuery.each(sections,function(i,val){
                    $('.filter_section').append($('<option>', {
                        value: val.id,
                        text: val.description
                    }));
                    $('.filter_section').chosen().trigger('chosen:updated');
                    $('.filter_section').siblings('.chosen-container').css({border:'2px solid red'});
                });
                $('.loading').hide();
            }
        })
    });
    $('.filter_section').on('change',function(){
        checkDestinationForm();
    });

    function putRoute(form)
    {
        console.log(form.data('id'));
        var route_no = form.data('route_no');
        $('#route_no').val(route_no);
        $('#op').val(0);
        $('#currentID').val(form.data('id'));
    }

    function changeRoute(form,id)
    {
        var route_no = form.data('route_no');
        $('#route_no').val(route_no);
        $('#op').val(id);
    }
    function checkDestinationForm(){
        var division = $('.filter-division').val();
        var section = $('.filter_section').val();
        if(division.length == 0){
            $('.filter-division').siblings('.chosen-container').css({border:'2px solid red'});
        }else{
            $('.filter-division').siblings('.chosen-container').css({border:'none'});
        }

        if(section.length == 0){
            $('.filter_section').siblings('.chosen-container').css({border:'2px solid red'});
        }else{
            $('.filter_section').siblings('.chosen-container').css({border:'none'});
        }
    }
    function checkDocTye(){
        var doc = $('select[name="doc_type"]').val();
        if(doc.length == 0){
            $('.error').removeClass('hide');
        }
    }
    function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },2000);
    }
