<tr id="{{ $_GET['count'] }}">
    <td id="border-bottom" class="align-top">
        <button type="button" value="{{ $_GET['count'] }}" onclick="erase($(this))" class="btn-sm"><small><i class="glyphicon glyphicon-remove"></i></small></button>
    </td>
    <td id="border-bottom">

    </td>
    <td id="border-bottom">

    </td>
    <td id="border-bottom" class="{{ 'description'.$_GET['count'] }} align-top">
        <div class="{{ 'description'.$_GET['count'] }}"></div>
            <strong><i>Description</i></strong>
            <textarea class="{{ 'textarea'.$_GET['count'] }}" placeholder="Place some text here" style="width: 100%;" name="description[]" id="{{ 'specification'.$_GET['count'] }}" class="ckeditor" onkeyup="trapping()" required></textarea>
            <small id="{{ 'E_specification'.$_GET['count'] }}"></small>
        </div>
        <div class="{{ 'expected'.$_GET['count'] }}">
            <strong><i>Expected:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="expected[]" id="{{ 'expected'.$_GET['count'] }}" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" style="width: 50%;display: inline" required>
            <small id="{{ 'E_expected' .$_GET['count'] }}">required!</small>
        </div>
        <div class="date_time1" style="margin-top: 2%">
            <strong><i>Date and Time:</i></strong> &nbsp;&nbsp;&nbsp;
            <input type="text" name="date_time[]" id="{{ 'date_time'.$_GET['count'] }}" class="form-control" onkeyup="trapping(event,true)" style="width: 50%;display: inline" required>
            <small id="{{ 'E_date_time'.$_GET['count'] }}">required!</small>
        </div>
    </td>
    <td id="border-bottom"></td>
    <td id="border-bottom" class="{{ 'unit_cost'.$_GET['count'] }} align-top">
        <input type="text" name="unit_cost[]" id="{{ 'unit_cost'.$_GET['count'] }}" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required><small id="{{ 'E_unit_cost'.$_GET['count'] }}">required!</small>
    </td>
    <td id="border-bottom" class="{{ 'estimated_cost'.$_GET['count'] }} align-top">
        <input type="hidden" name="estimated_cost[]" id="{{ 'estimated_cost'.$_GET['count'] }}" class="form-control">
        <strong style="color:green;" class="align-top">&#x20b1;</strong><strong style="color:green" id="{{ 'e_cost'.$_GET['count'] }}"></strong>
    </td>
</tr>
<script>
    var textarea_count = '<?php echo $_GET['count']; ?>';
    $(".textarea"+textarea_count).wysihtml5();
</script>
