<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrdersRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OrdersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrdersCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Orders::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/orders');
        CRUD::setEntityNameStrings('orders', 'orders');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    protected function setupShowOperation()
    {
      $this->crud->addColumn([
        'name' => 'users.name', // the relationships are handled automatically
        'label' => 'Artist', // the grid's column heading
        'type' => 'text'
      ]);
      $this->crud->addColumn([
        'name' => 'hasStatus.name', // the relationships are handled automatically
        'label' => 'Status', // the grid's column heading
        'type' => 'text'
      ]);

      $this->crud->column('date_of_issue');
      $this->crud->column('phone_number');
      $this->crud->column('name');
      $this->crud->column('desc');
      $this->crud->column('complexity');
      $this->crud->column('color_fabric');
      $this->crud->column('backdrop');
      $this->crud->column('quantity');
      $this->crud->column('size');
      $this->crud->column('prepayment');
      $this->crud->column('price');
      $this->crud->column('delivery');
      $this->crud->column('media');
    }

    protected function setupListOperation()
    {
      $this->crud->addColumn([
        'name' => 'users.name', // the relationships are handled automatically
        'label' => 'Artist', // the grid's column heading
        'type' => 'text'
      ]);
      $this->crud->addColumn([
        'name' => 'hasStatus.name', // the relationships are handled automatically
        'label' => 'Status', // the grid's column heading
        'type' => 'text'
      ]);

        $this->crud->column('date_of_issue');
        $this->crud->column('phone_number');
        $this->crud->column('name');
        $this->crud->column('desc');
        $this->crud->column('complexity');
        $this->crud->column('color_fabric');
        $this->crud->column('backdrop');
        $this->crud->column('quantity');
        $this->crud->column('size');
        $this->crud->column('prepayment');
        $this->crud->column('price');
        $this->crud->column('delivery');
        $this->crud->column('media');

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
            'date_of_issue' => 'required',
            'phone_number' => 'required',
            'name' => 'required',
            'desc' => 'required',
            'complexity' => 'required',
            'backdrop' => 'required',
            'quantity' => 'required',
            'size' => 'required',
            'prepayment' => 'required',
            'price' => 'required',
            'delivery' => 'required',
            'status_id' => 'required',
            'user_id' => 'required',
        ]);

        $this->crud->field('date_of_issue');
        $this->crud->field('phone_number');
        $this->crud->field('name');
        $this->crud->field('desc');
        $this->crud->field('complexity');
        $this->crud->field('color_fabric');
        $this->crud->field('backdrop');
        $this->crud->addField([
          'name'  => 'quantity', // The db column name
          'label' => 'Quantity', // Table column heading
          'type'  => 'number',
          'decimals'      => 2
        ]);
        $this->crud->field('size');
        $this->crud->field('prepayment');
        $this->crud->field('price');
        $this->crud->addField([
          // 1-n relationship
          'label'     => 'Artists', // Table column heading
          'type'      => 'select',
          'name'      => 'user_id', // the column that contains the ID of that connected entity;
          'entity'    => 'users', // the method that defines the relationship in your Model
          'attribute' => 'name', // foreign key attribute that is shown to user
          'model'     => "App\Models\User", // foreign key model
        ]);
        $this->crud->addField([
          'label' => 'Delivery',
          'type' => 'text',
          'name' => 'delivery',
          'value' => 'Tashkent'
        ]);
        // qqqq
        $this->crud->addField([
          // 1-n relationship
          'label'     => 'Status', // Table column heading
          'type'      => 'select',
          'name'      => 'status_id', // the column that contains the ID of that connected entity;
          'entity'    => 'hasStatus', // the method that defines the relationship in your Model
          'attribute' => 'name', // foreign key attribute that is shown to user
          'model'     => "App\Models\Status", // foreign key model
          'hint'      => 'Make sure stasuses are added before creating an order!',
          'events' => [
            'updating' => function ($entry) {
                $entry->author_id = backpack_user()->id;
            },
          ],
        ]);


        $this->crud->field('media');

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
