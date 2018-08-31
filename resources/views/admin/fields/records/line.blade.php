<div class="form-group form-element-text">
    <label for="data-label" class="control-label">
        Label
    </label>
    <input type="text" id="data-label" name="data[label]" value="{{ !empty(json_decode($entry['data'])->label) ? json_decode($entry['data'])->label : '' }}" class="form-control">
</div>

<div class="form-group form-element-text">
    <label for="data-borderColor" class="control-label">
        Border Color
    </label>
    <input type="color" id="data-borderColor" name="data[borderColor]" value="{{ !empty(json_decode($entry['data'])->borderColor) ? json_decode($entry['data'])->borderColor : '' }}" class="form-control">
</div>

<div class="form-group form-element-number">
    <label for="data-values" class="control-label">
        Values on X axis
    </label>
    <input type="text" id="data-values" name="data[values]" value="{{ !empty(json_decode($entry['data'])->values) ? json_decode($entry['data'])->values : '' }}" class="form-control">
    <small class="text-muted">Write numbers separated by commas without spaces</small>
</div>
