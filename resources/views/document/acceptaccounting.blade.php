
@extends('layouts.app')
@section('content')
    <div class="col-md-9 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">Accept Documents <small class="text-muted">[Accounting Section]</small> </h2>
            <form class="form-inline form-accept" id="accept_form">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" name="route_no" class="form-control route_no" disabled placeholder="Enter route #" autofocus>
                    <input type="text" name="dv_no" class="form-control dv_no" disabled placeholder="Enter DV #">
                    <button type="button" onclick="acceptDocument()" class="btn btn-success btn-accept"><i class="fa fa-plus"></i> Accept Document</button>
                </div>
                <div class="clearfix"></div><br>
                <div class="alert alert-danger error-accept hide">Please input route number!</div>
            </form>
            <hr />
            <div class="accepted-list">

            </div>
        </div>
    </div>
    @include('sidebar')
@endsection

@section('js')
    <script>
        $(window).load(function(){
            $('.route_no').prop("disabled", false); // Element(s) are now enabled.
            $('.dv_no').prop("disabled", false); // Element(s) are now enabled.
            $('.route_no').focus();
        });
        var route_nos = [];
        <?php echo 'var url="'. asset('accounting/accept').'";'; ?>
        function acceptDocument(){
            $('.loading').show();
            var dv_no = $('.dv_no').val();
            var route_no = $('.route_no').val();
            var content = '<div class="alert alert-info"><span class="pull-right"><a href="#" class="remove-accept" data-route="'+route_no+'"><i class="fa fa-times"></i></a></span><strong>ACCEPTED!</strong><br>Route Number: <strong>'+route_no+'</strong><br>DV Number: '+dv_no+'</div>';
            if(route_no){
                for(var i=0; i<route_nos.length; i++){
                    if(route_nos[i]==route_no){
                        $('.error-accept').removeClass('hide').fadeIn(500).html('Route # \''+route_no+'\' is already accepted!');
                        $('.loading').hide();
                        return false;
                    }
                }
                //post data to database
                var form = $('#accept_form');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(data) {
                        $('.loading').hide();
                        var jim = jQuery.parseJSON(data);
                        if(jim.message=='SUCCESS'){
                            route_nos.push(route_no);
                            $('.accepted-list').append(content);
                            $('.route_no').val(null).focus();
                            $('.dv_no').val(null);
                            $('.error-accept').addClass('hide').fadeOut(500);
                            //if remove accept
                            $('.remove-accept').on('click',function(){
                                $('.loading').show();
                                var tmp = $(this).data('route');
                                $(this).parent().parent().fadeOut(500);
                                for(var i=0; i<route_nos.length; i++){
                                    if(route_nos[i]==tmp){
                                        route_nos.splice(i,1);
                                        $.ajax({
                                            url: 'destroy/'+tmp,
                                            type: 'GET',
                                            success: function(data) {
                                                $('.loading').hide();
                                            }
                                        });
                                    }
                                }
                            });

                        }else if(jim.message=='DUPLICATE'){
                            $('.error-accept').removeClass('hide').fadeIn(500).html('DV # \''+dv_no+'\' is already in use!');
                            return false;
                        }else{
                            $('.error-accept').removeClass('hide').fadeIn(500).html('Route # \''+route_no+'\' not found in the database!');
                            return false;
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }else{
                $('.error-accept').removeClass('hide').fadeIn(500).html('Please input route number!');
                $('.route_no').focus();
                $('.loading').hide();
            }
        }
    </script>
@endsection
