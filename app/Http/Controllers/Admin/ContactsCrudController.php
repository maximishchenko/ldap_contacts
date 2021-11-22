<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContactsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Contacts::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contacts');
        CRUD::setEntityNameStrings(trans('messages.contact'), trans('messages.contacts_index_heading'));
        $this->crud->enableBulkActions();

        $this->crud->addField([
            'name' => 'displayName',
            'type' => 'text',
            'label' => trans("messages.contacts_fullname"),
            'tab' => trans("messages.contacts_base_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'mail',
            'type' => 'text',
            'label' => trans("messages.contacts_email"),
            'tab' => trans("messages.contacts_base_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'telephoneNumber',
            'type' => 'text',
            'label' => trans("messages.contacts_telephoneNumber"),
            'tab' => trans("messages.contacts_base_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'homePhone',
            'type' => 'text',
            'label' => trans("messages.contacts_homePhone"),
            'tab' => trans("messages.contacts_base_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'company',
            'type' => 'text',
            'label' => trans("messages.contacts_company"),
            'tab' => trans("messages.contacts_company_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'department',
            'type' => 'text',
            'label' => trans("messages.contacts_department"),
            'tab' => trans("messages.contacts_company_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'title',
            'type' => 'text',
            'label' => trans("messages.contacts_title"),
            'tab' => trans("messages.contacts_company_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'physicalDeliveryOfficeName',
            'type' => 'text',
            'label' => trans("messages.contacts_phisicalDeliveryOfficeName"),
            'tab' => trans("messages.contacts_company_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'sid',
            'type' => 'text',
            'label' => trans("messages.contacts_sid"),
            'tab' => trans("messages.contacts_service_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'whenChanged',
            'type' => 'datetime',
            'label' => trans("messages.contacts_whenChanged"),
            'tab' => trans("messages.contacts_service_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'sort',
            'type' => 'number',
            'label' => trans("messages.contacts_sort"),
            'tab' => trans("messages.contacts_service_tab"),
        ]);
        $this->crud->addField([
            'name' => 'status',
            'type' => 'checkbox',
            'label' => trans("messages.contacts_status"),
            'tab' => trans("messages.contacts_service_tab"),
            'attributes' => [
                'readonly' => false
            ],
        ]);
        $this->crud->addField([
            'name' => 'distinguishedName',
            'type' => 'text',
            'label' => trans("messages.contacts_distinguishedName"),
            'tab' => trans("messages.contacts_base_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);
        $this->crud->addField([
            'name' => 'manager',
            'type' => 'text',
            'label' => trans("messages.contacts_manager"),
            'tab' => trans("messages.contacts_base_tab"),
            'attributes' => [
                'readonly' => 'readonly'
            ],
        ]);

        // index and show action columns

        $this->crud->addColumn([
            'name' => 'status',
            'label' => trans("messages.contacts_status"),
            'type' => 'check'
        ])->beforeColumn('displayName'); //->beforeColumn('displayName')

        $this->crud->addColumn([
            'name' => 'displayName',
            'label' => trans("messages.contacts_fullname"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'company',
            'label' => trans("messages.contacts_company"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'department',
            'label' => trans("messages.contacts_department"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'title',
            'label' => trans("messages.contacts_title"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'physicalDeliveryOfficeName',
            'label' => trans("messages.contacts_phisicalDeliveryOfficeName"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'mail',
            'label' => trans("messages.contacts_email"),
            'type' => 'email',
        ]);
        $this->crud->addColumn([
            'name' => 'telephoneNumber',
            'label' => trans("messages.contacts_telephoneNumber"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'homePhone',
            'label' => trans("messages.contacts_homePhone"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'ipPhone',
            'label' => trans("messages.contacts_ipPhone"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'mobile',
            'label' => trans("messages.contacts_mobile"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'sort',
            'label' => trans("messages.contacts_sort"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'distinguishedName',
            'label' => trans("messages.contacts_distinguishedName"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'manager',
            'label' => trans("messages.contacts_manager"),
            'type' => 'text',
        ]);


        $this->crud->addColumn([
            'name' => 'sid',
            'label' => trans("messages.contacts_sid"),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'whenChanged',
            'label' => trans("messages.contacts_whenChanged"),
            'type' => 'text',
        ]);

        // Filters

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
        $this->crud->removeColumn('sid');
        $this->crud->removeColumn('whenChanged');
        $this->crud->removeColumn('sort');

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
        CRUD::setValidation(ContactsRequest::class);



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
