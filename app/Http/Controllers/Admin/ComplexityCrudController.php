<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComplexityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ComplexityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ComplexityCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Complexity::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/complexity');
        CRUD::setEntityNameStrings('complexity', 'complexities');
        $this->crud->orderBy('complexity_order', 'ASC');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('char');
        $this->crud->addColumn([
          'name' => 'currency.short_name', // the relationships are handled automatically
          'label' => 'Currency', // the grid's column heading
          'type' => 'text'
        ]);
        CRUD::column('value');
        CRUD::column('complexity_order');
        
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
            'char' => 'required|max:10',
            'currency_id' => 'required',
            'value' => 'required',
            'complexity_order' => 'required|max:3',
        ]);

        $this->crud->field('char');
        $this->crud->addField([
          // 1-n relationship
          'label'     => 'Currency', // Table column heading
          'type'      => 'select',
          'name'      => 'currency_id', // the column that contains the ID of that connected entity;
          'entity'    => 'currency', // the method that defines the relationship in your Model
          'attribute' => 'short_name', // foreign key attribute that is shown to user
          'model'     => "App\Models\Currency", // foreign key model
        ]);
        $this->crud->addField([
          'name'  =>  'value',
          'label' =>  'Value',
          'type'  => 'number',
          'attributes' => ["step" => "any"], // allow decimals
          // 'suffix'     => ".00",
        ]);
        $this->crud->addField([
          'name'  => 'complexity_order',
          'label' => 'Complexity order',
          'type'  => 'number',
          'default' => 5,
          'hint'  => 'Maximum up to 999'
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
