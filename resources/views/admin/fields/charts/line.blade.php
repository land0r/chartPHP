<div class="form-group form-element-number">
    <label for="data-labels" class="control-label">
        Axis X labels
    </label>
    <input type="text" id="data-labels" name="data[labels]" value="{{ !empty(json_decode($entry['data'])->labels) ? json_decode($entry['data'])->labels : '' }}" class="form-control">
    <small class="text-muted">Write numbers separated by commas without spaces</small>
</div>
