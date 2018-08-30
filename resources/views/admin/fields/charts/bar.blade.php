<div class="form-group form-element-number">
    <label for="data-x-axes" class="control-label">
        Value title
    </label>
    <input type="text" id="data-x-axes" name="data[title]" value="{{ !empty(json_decode($entry['data'])->title) ? json_decode($entry['data'])->title : '' }}" class="form-control">
</div>
