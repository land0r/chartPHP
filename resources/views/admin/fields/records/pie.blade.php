<div class="form-group form-element-text">
    <label for="data-label" class="control-label">
        Label
    </label>
    <input type="text" id="data-label" name="data[label]" value="{{ !empty(json_decode($entry['data'])->label) ? json_decode($entry['data'])->label : '' }}" class="form-control">
</div>

<div class="form-group form-element-text">
    <label for="data-backgroundColor" class="control-label">
        Background Color
    </label>
    <input type="color" id="data-backgroundColor" name="data[backgroundColor]" value="{{ !empty(json_decode($entry['data'])->backgroundColor) ? json_decode($entry['data'])->backgroundColor : '' }}" class="form-control">
</div>

<div class="form-group form-element-number">
    <label for="data-value" class="control-label">
        Value {{ !empty($chartData->title) ? ' - ' . $chartData->title : '' }}
    </label>
    <input type="number" step="any" id="data-value" name="data[value]" value="{{ !empty(json_decode($entry['data'])->value) ? json_decode($entry['data'])->value : '' }}" class="form-control">
</div>
