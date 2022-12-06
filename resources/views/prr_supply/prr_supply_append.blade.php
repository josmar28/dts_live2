<tr id="{{ $_GET['count'] }}">
    <td id="border-bottom" class="align-top"><button type="button" value="{{ $_GET['count'] }}" onclick="erase($(this))" class="btn-sm"><small><i class="glyphicon glyphicon-remove"></i></small></button></td>
    <td id="border-bottom" class="{{ 'issue'.$_GET['count'] }} align-top"><input type="text" name="issue[]" id="{{ 'issue'.$_GET['count'] }}" class="form-control" onkeyup="trapping()" required><small id="{{ 'E_issue'.$_GET['count'] }}">required!</small></td>
    <td id="border-bottom" class="{{ 'description'.$_GET['count'] }} align-top">
        <input type="text" name="description[]" id="{{ 'description'.$_GET['count'] }}" class="form-control" onkeyup="trapping()" required><small id="{{ 'E_description'.$_GET['count'] }}">required!</small>
        <br><strong><i>Specification(s)</i></strong>
        <textarea class="{{ 'textarea'.$_GET['count'] }}" placeholder="Place some text here" style="width: 100%;" name="specification[]" id="{{ 'specification'.$_GET['count'] }}" class="ckeditor" onkeyup="trapping()"></textarea><small id="{{ 'E_specification'.$_GET['count'] }}"></small>
    </td>
    <td id="border-bottom" class="{{ 'qty'.$_GET['count'] }} align-top"><input type="text" name="qty[]" id="{{ 'qty'.$_GET['count'] }}" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required><small id="{{ 'E_qty'.$_GET['count'] }}">required!</small></td>
    <td id="border-bottom" class="{{ 'unit_cost'.$_GET['count'] }} align-top" colspan="2">
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
