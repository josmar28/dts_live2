<form action="{{ asset('/form/justification/letter') }}" method="POST" id="form_route"
      xmlns="http://www.w3.org/1999/html">
    {{ csrf_field() }}
    <input type="hidden" name="doctype" value="JUST_LETTER" />
    <div class="modal-body">
        <table>
            <tr>
                <td class="col-md-1"><img height="130" width="130" src="{{ asset('resources/img/ro7.png') }}" /></td>
                <td class="col-lg-10" style="text-align: center;">
                    Repulic of the Philippines <br />
                    <strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO. VII</strong><br />
                    Osmeña Boulevard, Cebu City, 6000 Philippines <br />
                    Regional Director's Office Tel. No. (032) 253-635-6355 Fax No. (032) 254-0109 <br />
                    Official Website:<a target="_blank" href="http://www.ro7.doh.gov.ph"><u>http://www.ro7.doh.gov.ph</u></a> Email Address: dohro7{{ '@' }}gmail.com
                </td>
                <td class="col-md-10"><img height="130" width="130" src="{{ asset('resources/img/ro7.png') }}" /> </td>
            </tr>
        </table>
        <hr style="border: 1px solid #333;" />
        <div class="container-fluid">
            <div class="row">
                <label class="col-md-2">Prepared by :</label>
                <label class="col-md-6" style="font-weight: bolder;font-size: medium;color: #985f0d;">{{ $user }}</label>
            </div>
            <div class="row">
                <div class="col-md-2"><strong>To</strong></div>
                <div class="col-md-10" id="next">
                    <div class="row">
                        <span class="col-md-4">Name</span>
                        <span class="col-md-4">Designation</span>
                    </div>
                    <div class="row">
                        <span class="col-md-4">
                            <span class="form-group">
                                <input type="text" name="name_to[]" class="form-control" id="to-name-1" />
                            </span>
                        </span>
                        <span class="col-md-4">
                            <span class="form-group">
                                <input type="text" name="desig_to[]" class="form-control" id="to-des-1" />
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-10">
                    <br />
                    <a href="#" id="add" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add new</a>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-2"><strong>Thru</strong></div>
                <div class="col-md-10">
                    <div class="row">
                        <span class="col-md-4">Name</span>
                        <span class="col-md-4">Designation</span>
                    </div>
                    <div class="row">
                        <span class="col-md-4">
                            <span class="form-group">
                                <input type="text" name="name_thru[]" class="form-control has-error" id="thru-name-1" />
                            </span>
                        </span>
                        <span class="col-md-4">
                            <span class="form-group">
                                <input type="text" name="desig_thru[]" class="form-control has-error" id="thru-des-1" />
                            </span>
                        </span>
                        <span class="col-md-1">
                            <span class="glyphicon glyphicon-plus" style="color:#5cb85c;" onclick="add_thru_field(this);" aria-hidden="true"></span>
                        </span>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-2"><strong>Message/Remarks</strong></div>
                <div class="col-md-10">
                    <textarea class="form-control" name="description" id="description" rows="10" style="resize:none;" required></textarea>
                    <script>CKEDITOR.replace( 'description');</script>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
<script>
    /**
     * Created by lourence on 12/28/2016.
     */
    var to_name = 1;
    var to_des = 1;
    var thru_name = 1;
    var thru_des = 1;
    var error = false;
    var name_show_error = false;
    var des_show_error = false;

    function remove_to(el){
        $(el).parent().parent().remove();
        to_name --;
        to_des --;
    }

    (function($){


        $('#add').click(function(evt){

            var el = this;
            var parent = $(el).parent();

            console.log(parent);

            var to = $('#to-name-' + to_name);
            var des = $('#to-des-' + to_des);
            if(isEmpty(to.val())) {
                if(!name_show_error){
                    to.parent().addClass(' has-error');
                    to.parent().append("<label style='color:red;'>Required</label>");
                    name_show_error = true;
                }
                error = true;
            } else {
                error = false;
                to.parent().removeClass(' has-error');
                to.parent().find('label').remove();
            }

            if(isEmpty(des.val())){
                if(!des_show_error){
                    des.parent().addClass(' has-error');
                    des.parent().append("<label style='color:red;'>Required</label>");
                    des_show_error = true;
                }
                error = true;
            } else {
                error = false;
                des.parent().removeClass(' has-error');
                des.parent().find('label').remove();
            }
            if(isEmpty(des.val()) || isEmpty(to.val())){
                error = true;
            }

            if(! error) {
                name_show_error = false;
                des_show_error = false;
                to_name++;
                to_des++;
                $('#next').append('' +
                        '<div class="row"> ' +
                        '<br />' +
                        '<span class="col-md-4"> ' +
                        '<span class="form-group"> ' +
                        '<input type="text" name="name_to[]" class="form-control" id="to-name-' + to_name + '"/> ' +
                        '</span>' +
                        '</span> ' +
                        '<span class="col-md-4"> ' +
                        '<input type="text" name="desig_to[]" class="form-control" id="to-des-' + to_des + '"/> ' +
                        '</span> ' +
                        '<span class="col-md-1"> ' +
                        '<a href="#" onclick="remove_to(this);"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>' +
                        '</span>' +
                        '</div>');
            }
            console.log("To : " + to_name);
            console.log("Thru : " + to_des);
        });

        $('#remove').click(function(evt){
            alert("hello");
        });

        var add_thru_field = function(el){
            var parent = $(el).parent().parent().parent();
            parent.append('' +
                    '<div class="row"> ' +
                    '<br />' +
                    '<span class="col-md-4"> ' +
                    '<span class="form-group"> ' +
                    '<input type="text" name="name_to[]" class="form-control" id="to-name-' + to_name + '"/> ' +
                    '</span>' +
                    '</span> ' +
                    '<span class="col-md-4"> ' +
                    '<input type="text" name="desig_to[]" class="form-control" id="to-des-' + to_des + '"/> ' +
                    '</span> ' +
                    '<span class="col-md-1"> ' +
                    '<a href="#" style="color:#5cb85c;" onclick="add_to_field(this);"> <span class="glyphicon glyphicon-plus"  aria-hidden="true"></span></a>' +
                    '<a href="#" style="color:red;" onclick="remove_field(this);"> <span class="glyphicon glyphicon-minus"  aria-hidden="true"></span></a>' +
                    '</span>' +
                    '</div>');
        };

    })($);

    (function($){
        $('input[type="text"]').val();
    })($);
</script>