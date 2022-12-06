<tr>
    <td class="col-sm-3"><label>Section</label></td>
    <td class="col-sm-1">:</td>
    <td class="col-sm-8">
        <select name="section" id="section" class="chosen-select form-control" required>
            <option value="" selected disabled>Select section</option>
            @foreach($section as $sec)
                <option value="{{ $sec->id }}">{{ $sec->description }}</option>
            @endforeach
        </select>
    </td>
</tr>

<script>
    $('.chosen-select').chosen();
</script>
