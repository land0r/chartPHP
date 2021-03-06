<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chart;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CreateChartRecordRequest as StoreRequest;
use App\Http\Requests\UpdateChartRecordRequest as UpdateRequest;

/**
 * Class ChartRecordCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ChartRecordCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ChartRecord');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/chart_record');
        $this->crud->setEntityNameStrings('chart record', 'Chart Records');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $charts = Chart::where('user_id', '=', auth()->user()->id)->get();
        $chartIds = [];

        foreach ($charts as $chart) {
            $chartIds[] = $chart->id;
        }

        $this->crud->addClause(
            'whereIn',
            'chart_id',
            $chartIds
        );

        // Columns
        $this->crud->addColumns(
            [
                [
                    'name' => 'chart.id',
                    'type' => 'text',
                    'label' => "Chart ID",
                ],
                [
                    'name' => 'chart.name',
                    'type' => 'text',
                    'label' => "Chart name",
                ],
                [
                    'name' => 'data',
                    'label' => 'Data',
                    'type' => 'closure',
                    'function' => function($entry) {
                        $values = json_decode($entry->data, true);

                        return $this->formatData($values);
                    }
                ],
                [
                    'name' => "created_at",
                    'label' => "Created date",
                    'type' => "datetime",
                ]
            ]
        );

        // Fields
        $this->crud->addField(
            [ // select_from_array
                'name' => 'chart_id',
                'label' => "Chart",
                'type' => 'select2_from_array',
                'options' => $charts
                    ->pluck('name', 'id')
                    ->toArray(),
                'allows_null' => false,
            ], 'create'
        );

        // Filters
        $this->crud->addFilter([ // select2 filter
            'name' => 'chart_id',
            'type' => 'select2',
            'label'=> 'Chart'
        ], function() {
            return Chart::all()
                ->where('user_id', '=', auth()->user()->id)
                ->keyBy('id')
                ->pluck('name', 'id')
                ->toArray();
        }, function($value) {
             $this->crud->addClause('where', 'chart_id', $value);
        });

        // add asterisk for fields that are required in ChartRecordRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $request->merge(['data' => json_encode($request->input('data'))]);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        $this->data['fields'][] = [
            'name' => 'chart-data',
            'type' => 'view',
            'view' => 'admin.fields.records.' . $this->data['entry']->chart->chart_type
        ];

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    /**
     * Format array to column view
     *
     * @param string $values
     *
     * @return string $output
     */
    public function formatData($values)
    {
        if(empty($values)) {
            $output = 'This record has no data yet';
        } else {
            $output = (is_array($values)) ? "<ul>" : "";

            foreach($values as $key => $value) {
                if(is_array($value) || is_object($value)) {
                    $output .= "<li>" . ucfirst($key) . ":</li>";
                    $output .= $this->formatData($value);
                } else {
                    if ($key == 'borderColor' || $key == 'backgroundColor') {
                        $output .= "<li>" . ucfirst($key) .
                            ": <i class='fa fa-square' aria-hidden='true' style='color:$value' title='$value'></i>" .
                            "</li>";
                    } else {
                        $output .= "<li>" . ucfirst($key) . ": " . $value . "</li>";
                    }
                }
            }

            $output .= (is_array($values)) ? "</ul>" : "";
        }

        return $output;
    }
}
