<div class="form-group form-element-number">
    <label for="data-x-axes" class="control-label">
        Axis X Title
    </label>
    <input type="text" id="data-x-axes" name="data[xAxes]" value="{{ !empty(json_decode($entry['data'])->xAxes) ? json_decode($entry['data'])->xAxes : '' }}" class="form-control">
</div>

<div class="form-group form-element-number">
    <label for="data-y-axis" class="control-label">
        Axis Y Title
    </label>
    <input type="text" id="data-y-axis" name="data[yAxes]" value="{{ !empty(json_decode($entry['data'])->yAxes) ? json_decode($entry['data'])->yAxes : '' }}" class="form-control">
</div>
