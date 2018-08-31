<div class="form-group form-element-text">
    <label for="data[label]" class="control-label">
        Label
    </label>
    <input type="text" id="data-label" name="data[label]" value="{{ !empty(json_decode($entry['data'])->label) ? json_decode($entry['data'])->label : '' }}" class="form-control">
</div>

<div class="form-group form-element-text">
    <label for="data[backgroundColor]" class="control-label">
        Background Color
    </label>
    <input type="color" id="data-backgroundColor" name="data[backgroundColor]" value="{{ !empty(json_decode($entry['data'])->backgroundColor) ? json_decode($entry['data'])->backgroundColor : '' }}" class="form-control">
</div>

<div class="form-group form-element-text">
    <label for="data[borderColor]" class="control-label">
        Border Color
    </label>
    <input type="color" id="data-borderColor" name="data[borderColor]" value="{{ !empty(json_decode($entry['data'])->borderColor) ? json_decode($entry['data'])->borderColor : '' }}" class="form-control">
</div>

<div class="form-group form-element-number">
    <label for="data[data][x]" class="control-label">
        Value X
    </label>
    <input type="number" step="any" id="data-value" name="data[data][x]" value="{{ !empty(json_decode($entry['data'])->data->x) ? json_decode($entry['data'])->data->x : '' }}" class="form-control">
</div>

<div class="form-group form-element-number">
    <label for="data[data][y]" class="control-label">
        Value Y
    </label>
    <input type="number" step="any" id="data-value" name="data[data][y]" value="{{ !empty(json_decode($entry['data'])->data->y) ? json_decode($entry['data'])->data->y : '' }}" class="form-control">
</div>
