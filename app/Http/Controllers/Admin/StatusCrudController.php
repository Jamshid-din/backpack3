<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StatusRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StatusCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Status::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/status');
        CRUD::setEntityNameStrings('status', 'statuses');
        $this->crud->orderBy('status_order', 'ASC');

        if (!backpack_user()->can('status list')) {
          $this->crud->denyAccess('list');
        }
        if (!backpack_user()->can('status create')) {
          $this->crud->denyAccess('create');
        }
        if (!backpack_user()->can('status update')) {
          $this->crud->denyAccess('update');
        }
        $this->crud->denyAccess('delete');
    }

    protected function setupShowOperation()
    {
      $this->crud->column('name');
      $this->crud->addColumn([
        'name' => 'color',
        'label' => 'Color',
        'type' => 'custom_html',
        'value'    => function ($entry)
        {
          return "<button class='btn' style='background-color: ".$entry->color.";'>".$entry->color."</button>";
        },

      ]);
      $this->crud->column('status_order');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->column('name');
        $this->crud->addColumn([
          'name' => 'color',
          'label' => 'Color',
          'type' => 'custom_html',
          'value'    => function ($entry)
          {
            return "<button class='btn' style='background-color: ".$entry->color.";'>".$entry->color."</button>";
          },

        ]);
        $this->crud->column('status_order');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required',
            'status_order' => 'required|max:3',
        ]);

        $this->crud->field('name');
        $this->crud->addField([
          'name' => 'color',
          'label' => 'Color',
          'type' => 'color',
        ]);
        $this->crud->addField([
          'name'  =>  'status_order',
          'label' =>  'Status order',
          'type'  =>  'number',
          'default' => 5,
          'hint' => 'Maximum 999'
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
