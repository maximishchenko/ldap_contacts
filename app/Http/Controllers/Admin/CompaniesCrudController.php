<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompaniesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Companies;

/**
 * Class CompaniesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompaniesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::setModel(\App\Models\Companies::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/companies');
        CRUD::setEntityNameStrings('companies', 'companies');
        CRUD::setEntityNameStrings(trans('messages.companies'), trans('messages.companies'));
        $this->crud->enableBulkActions();

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => trans("messages.companies_name"),
            'tab' => trans("messages.companies_main_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'parent_id',
            'type' => 'select2_from_array',
            'label' => trans("messages.parent_company"),
            'tab' => trans("messages.companies_main_tab"),
			'options' => Companies::getParentsArray(),
        ]);
        $this->crud->addField([
            'name' => 'address',
            'type' => 'text',
            'label' => trans("messages.companies_address"),
            'tab' => trans("messages.companies_main_tab"),
        ]);
        $this->crud->addField([
            'name' => 'email',
            'type' => 'email',
            'label' => trans("messages.companies_email"),
            'tab' => trans("messages.companies_main_tab"),
        ]);
        $this->crud->addField([
            'name' => 'reception_phone',
            'type' => 'text',
            'label' => trans("messages.companies_reception_phone"),
            'tab' => trans("messages.companies_phones_tab"),
        ]);
        $this->crud->addField([
            'name' => 'phone',
            'type' => 'text',
            'label' => trans("messages.companies_phone"),
            'tab' => trans("messages.companies_phones_tab"),
        ]);
        $this->crud->addField([
            'name' => 'fax_city',
            'type' => 'text',
            'label' => trans("messages.companies_fax_city"),
            'tab' => trans("messages.companies_phones_tab"),
        ]);
        $this->crud->addField([
            'name' => 'slug',
            'type' => 'text',
            'label' => trans("messages.companies_slug"),
            'tab' => trans("messages.companies_service_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'sort',
            'type' => 'number',
            'label' => trans("messages.companies_sort"),
            'tab' => trans("messages.companies_service_tab"),
            'attributes' => [
//                'readonly' => false
            ],
        ]);
        $this->crud->addField([
            'name' => 'status',
            'type' => 'checkbox',
            'label' => trans("messages.companies_status"),
            'tab' => trans("messages.contacts_service_tab"),
            'attributes' => [
                'readonly' => false
            ],
        ]);


        $this->crud->addColumn([
            'name' => 'status',
            'label' => trans("messages.companies_status"),
            'type' => 'check'
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => trans("messages.companies_name"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'parent_id',
            'label' => trans("messages.parent_id"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'sort',
            'label' => trans("messages.companies_sort"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'address',
            'label' => trans("messages.companies_address"),
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'reception_phone',
            'label' => trans("messages.companies_reception_phone"),
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'phone',
            'label' => trans("messages.companies_phone"),
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'fax_city',
            'label' => trans("messages.companies_fax_city"),
            'type' => 'text'
        ]);


        $this->crud->addFilter([
                'name' => 'status',
                'type' => 'dropdown',
                'label'=> trans("messages.contacts_status")
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
        // Export
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
        CRUD::setValidation(CompaniesRequest::class);



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
