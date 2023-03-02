<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TelegramConfigRequest;
use App\Models\TelegramConfig;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Http;
use Prologue\Alerts\Facades\Alert;

/**
 * Class TelegramConfigCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TelegramConfigCrudController extends CrudController
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

        CRUD::setModel(TelegramConfig::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/telegram-config');
        CRUD::setEntityNameStrings('telegram config', 'telegram configs');
        $telegram_config = TelegramConfig::first();

        $this->crud->denyAccess('delete'); 
        if ($telegram_config) {
          $this->crud->denyAccess('create');
        }
    }

    protected function setupShowOperation(){
      $this->setupListOperation();
    }
    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->column('token');
        $this->crud->column('chat_id');
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
      $this->crud->removeSaveActions(['save_and_edit', 'save_and_back', 'save_and_new']);

        $this->crud->setValidation([
            'token' => 'required',
            'chat_id' => 'required',
        ]);

        $this->crud->field('token');
        $this->crud->field('chat_id');

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
      $this->crud->removeSaveAction('save_action_one');

        $this->setupCreateOperation();
    }

    public function sendTestMessage($token, $chat_id)
    {

      $url = 'https://api.telegram.org/bot'.$token.'/sendMessage';

      $arrayQuery = array(
        'chat_id' => $chat_id,
        'text' => 'Hello, Sending test messsage to test telegram configs...'
      );		

      $response = Http::post($url, $arrayQuery);
      $response_body = json_decode($response->body());

      return $response;
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        $token = $_REQUEST['token'];
        $chat_id = $_REQUEST['chat_id'];

        $response = $this->sendTestMessage($_REQUEST['token'], $_REQUEST['chat_id']);

        $response_body = json_decode($response->body());

        if (!$response_body->ok) {
          Alert::add('error', $response_body->error_code.'<br>Wrong configs!<br>'.$response_body->description)->flash();
          return back();
        }  

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;
        // show a success message
        Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        // $this->crud->setSaveAction();
        $this->crud->setSaveAction('save_and_preview');

        return $this->crud->performSaveAction($item->getKey());
    }
}
