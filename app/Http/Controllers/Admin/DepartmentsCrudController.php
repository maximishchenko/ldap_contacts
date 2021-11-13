<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DepartmentsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DepartmentsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DepartmentsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Departments::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/departments');
        CRUD::setEntityNameStrings(trans('messages.departments'), trans('messages.departments'));
        $this->crud->enableBulkActions();

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => trans("messages.departments_name"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'sort',
            'type' => 'number',
            'label' => trans("messages.departments_sort"),
        ]);
        $this->crud->addField([
            'name' => 'status',
            'type' => 'checkbox',
            'label' => trans("messages.departments_status"),
            'attributes' => [
                'readonly' => false
            ],
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => trans("messages.departments_status"),
            'type' => 'check'
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => trans("messages.departments_name"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'sort',
            'label' => trans("messages.departments_sort"),
            'type' => 'text',
        ]);

        $this->crud->addFilter([
                'name' => 'status',
                'type' => 'dropdown',
                'label'=> trans("messages.departments_status")
            ], function() {
                return [
                    true => trans('messages.contacts_status_active'),
                    false => trans('messages.contacts_status_blocked'),
                ];
          }, function($value) {
                  $this->crud->addClause('where', 'status', $value);
          }
        );
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
        // $this->crud->enableExportButtons();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DepartmentsRequest::class);



        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
        $this->crud->removeSaveActions(['save_and_edit', 'save_and_new','save_and_back']);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
