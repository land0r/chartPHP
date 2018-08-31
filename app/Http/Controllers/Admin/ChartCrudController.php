<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CreateChartRequest as StoreRequest;
use App\Http\Requests\UpdateChartRequest as UpdateRequest;

/**
 * Class ChartCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ChartCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Chart');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/chart');
        $this->crud->setEntityNameStrings('chart', 'charts');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addClause('where', 'user_id', '=', auth()->user()->id);
        $this->crud->allowAccess('show');

        // Columns
        $this->crud->addColumns(
            [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Name'
                ],
                [
                    'name' => 'chart_type',
                    'type' => 'text',
                    'label' => 'Chart Type'
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
            ]
        );

        // Create fields
        $this->crud->addFields(
            [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Name'
                ],
                [
                    'name' => 'chart_type',
                    'type' => 'select_from_array',
                    'options' => [
                        'bar' => 'Bar Chart',
                        'bubble' => 'Bubble Chart',
                        'pie' => 'Pie Chart',
                        'line' => 'Line Chart',
                    ],
                    'allows_null' => false,
                    'default' => 'bar',
                    'label' => 'Chart Type'
                ]
            ], 'create'
        );

        // Update field
        $this->crud->addField(
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name'
            ], 'update'
        );

        // add asterisk for fields that are required in ChartRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param StoreRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse $redirect_location
     */
    public function store(StoreRequest $request)
    {
        // set user_id param
        $request->merge(['user_id' => auth()->user()->id]);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return string $content
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->addColumn(
            [
                'name' => 'chart_view',
                'label' => "Chart view",
                'type' => 'view',
                'view' => 'admin.chart',
            ]
        );

        $this->crud->addButtonFromView('line', 'edit_records', 'edit_records', 'end');

        return $content;
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
            'view' => 'admin.fields.charts.' . $this->data['entry']->chart_type
        ];

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    /**
     * Update the specified resource in the database.
     *
     * @param UpdateRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse $redirect_location
     */
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
