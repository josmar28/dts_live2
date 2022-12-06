/**
 * Created by lourence on 12/28/2016.
 */

(function($){

    var to_name = 1;
    var to_des = 1;
    var thru_name = 1;
    var thru_des = 1;
    var error = false;
    var name_show_error = false;
    var des_show_error = false;

    $('#add').click(function(evt){
        alert("Clicked inside a closure function");
    });

    var add_to_field = function(el){

        console.log("Top function  error =" + error);
        var parent = $(el).parent().parent().parent();
        console.log(parent);

        var to = $('#to-name-' + to_name);
        var des = $('#to-des-' + to_des);
        if(isEmpty(to.val())) {
            console.log("to name is required");
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
            console.log("to designation is required");
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
        if(! error) {
            name_show_error = false;
            des_show_error = false;
            to_name++;
            to_des++;
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
                '<a href="#" style="color:red;" onclick="remove_field(this);"> <span class="glyphicon glyphicon-remove"  aria-hidden="true"></span></a>' +
                '</span>' +
                '</div>');
        }
        console.log("To : " + to_name);
        console.log("Thru : " + to_des);
    };

    var remove_field = function(el){
        $(el).parent().parent().remove();
        to_name --;
        to_des --;
    };

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