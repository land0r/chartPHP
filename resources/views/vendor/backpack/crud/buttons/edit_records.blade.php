@if ($crud->hasAccess('update'))
    <a class="btn btn-xs btn-default" target="_blank" href="{{ '/admin/chart_record?chart_id=' . $entry->getKey() }}" data-toggle="tooltip" title="View and edit chart records">
        <i class="fa fa-edit"></i> Edit chart records
    </a>
@endif
