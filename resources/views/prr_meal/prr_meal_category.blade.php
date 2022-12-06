@if($_GET['type'] == 'category')
<div id="{{ $_GET['category_count'] }}" style="margin-top:2%;">
    <strong><i>Category:</i></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select name="category[][0]" id="category1" class="form-control" style="width: 50%;display: inline;">
        <option value="">Select Category</option>
        <option value="AM Snacks">AM Snacks</option>
        <option value="PM Snacks">PM Snacks</option>
        <option value="Buffet Lunch">Buffet Lunch</option>
    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="#" data-value="{{ $_GET['category_count'] }}" type="button" onclick="remove_category($(this))" style="display:inline;color: red;"><i class="fa fa-remove"> Remove</i></a>
</div>
@elseif($_GET['type'] == 'unit_cost')
<div id="{{ $_GET['category_count'] }}">
    <div style="margin-bottom: 10%;">
        <input type="text" name="unit_cost[]" id="unit_cost1" class="form-control" onkeydown="trapping(event,true)" onkeyup="trapping(event,true)" required>
    </div>
</div>
@elseif($_GET['type'] == 'estimated_cost')
    <div id="{{ $_GET['category_count'] }}">
        <div style="margin-bottom: 25%;">
            <input type="hidden" name="estimated_cost[]" id="estimated_cost1" class="form-control">
            <strong style="color:green;">&#x20b1;</strong><strong style="color:green" id="e_cost1"></strong>
        </div>
    </div>
@endif