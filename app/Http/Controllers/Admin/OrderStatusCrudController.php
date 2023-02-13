<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderStatusRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OrderStatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrderStatusCrudController extends CrudController
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
        CRUD::setModel(\App\Models\OrderStatus::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order-status');
        CRUD::setEntityNameStrings('order status', 'order statuses');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
          'name' => 'order_id',
          'label' => 'Order ID',
          'type' => 'text'
        ]);
        $this->crud->addColumn([
          'name' => 'status.name', // the relationships are handled automatically
          'label' => 'Status', // the grid's column heading
          'type' => 'text'
        ]);
        $this->crud->addColumn([
          'name' => 'created_at', // the relationships are handled automatically
          'label' => 'Updated at', // the grid's column heading
          'type' => 'datetime'
        ]);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    protected function setupShowOperation()
    {
      $this->crud->addColumn([
        'name' => 'order_id',
        'label' => 'Order ID',
        'type' => 'text'
      ]);
      $this->crud->addColumn([
        'name' => 'status.name', // the relationships are handled automatically
        'label' => 'Status', // the grid's column heading
        'type' => 'text'
      ]);
      $this->crud->addColumn([
        'name' => 'updated_at', // the relationships are handled automatically
        'label' => 'Updated at', // the grid's column heading
        'type' => 'datetime'      
      ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            // 'name' => 'required|min:2',
        ]);

        CRUD::field('order_id');
        CRUD::field('status_id');
        $this->crud->addField([
          'name' => 'created_at', // the relationships are handled automatically
          'label' => 'Updated at', // the grid's column heading
          'type' => 'datetime'      
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
