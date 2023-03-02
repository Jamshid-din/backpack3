<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrdersRequest;
use App\Models\Orders;
use App\Models\OrderStatus;
use App\Models\TelegramConfig;
use App\Models\TelegramInfo;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Prologue\Alerts\Facades\Alert as FacadesAlert;

/**
 * Class OrdersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrdersCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    
    // For Telegram Bot

    protected $telegram_details = [
      'token' => '',
      'chatId' => '',
      'channel' => '',
      'text'  => '',
    ];
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Orders::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/orders');
        CRUD::setEntityNameStrings('orders', 'orders');
        $this->crud->denyAccess('delete');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    protected function setupShowOperation()
    {
      if (!backpack_user()->can('edit orders')) {
        $this->crud->denyAccess('edit');
      }
      if (!backpack_user()->can('delete orders')) {
        $this->crud->denyAccess('delete');
      }
      $this->crud->column('id')->makeFirst();
      $this->crud->column('created_at');

      $this->crud->addColumn([
        'name' => 'users.name', // the relationships are handled automatically
        'label' => 'Artist', // the grid's column heading
        'type' => 'text'
      ]);
      $this->crud->addColumn([
        'name' => 'hasStatus.color', // the relationships are handled automatically
        'label' => 'Status', // the grid's column heading
        'type' => 'custom_html',
        'value' => function ($entry)
        {
            return "<button class='btn' style='background-color: ".$entry->hasStatus->color.";'></button> ".$entry->hasStatus->name;
        }
      ]);

      $this->crud->column('date_of_issue');
      $this->crud->column('phone_number');
      $this->crud->column('client_name');
      $this->crud->column('desc');
      $this->crud->column('complexity');
      $this->crud->column('color_fabric');
      $this->crud->column('backdrop');
      $this->crud->column('quantity');
      $this->crud->column('size');
      $this->crud->column('prepayment');
      $this->crud->column('price');
      $this->crud->column('delivery');
      $this->crud->addColumn(
        [
          // run a function on the CRUD model and show its return value
          'name'  => 'telegram_link',
          'label' => 'Telegram link', // Table column heading
          'type'  => 'custom_html',
          'value' => function ($entry) {
            return '<a href="'.$entry->telegram_link.'" target="_blank">'.$entry->telegram_link.'</a>';
          }
       ]);

    
      $this->crud->addColumn([
        'name' => 'photos',
        'label' => 'Photos',
        'type'  => 'custom_multiple_upload',
        'upload'    => true,
        'disk'      => 'uploads',
      ]);
      Widget::add(
        [
          'type'     => 'script',
          'content'  => 'assets/js/crud/custom-orders-show.js',
          // optional
          'stack'    => 'after_scripts', // default is after_scripts
        ]
      );
    }

    protected function setupListOperation()
    {
      $this->crud->enableResponsiveTable();
        if (!backpack_user()->can('edit orders')) {
          $this->crud->denyAccess('edit');
        }
        if (!backpack_user()->can('delete orders')) {
          $this->crud->denyAccess('delete');
        }
        $this->crud->column('id')->makeFirst();
        $this->crud->addColumn([
          'name' => 'created_at', // the relationships are handled automatically
          'label' => 'Created at', // the grid's column heading
          'type' => 'date',
          'orderable'  => true,
        ]);

        $this->crud->addColumn([
          // 1-n relationship
          'label'     => ' User name ', // Table column heading
          'type'      => 'custom_users',
          'name'      => 'user_id', // the column that contains the ID of that connected entity;
          'entity'    => 'users', // the method that defines the relationship in your Model
          'attribute' => 'name', // foreign key attribute that is shown to user
          'model'     => "App\Models\User", // foreign key model
        ]);

        $this->crud->addColumn([
          // 1-n relationship
          'label'     => 'Status', // Table column heading
          'type'      => 'custom_status',
          'name'      => 'status_id', // the column that contains the ID of that connected entity;
          'entity'    => 'hasStatus', // the method that defines the relationship in your Model
          'attribute' => 'name', // foreign key attribute that is shown to user
          'model'     => "App\Models\Status", // foreign key model
        ]);

        $this->crud->column('date_of_issue');
        $this->crud->column('phone_number');
        $this->crud->column('client_name');
        $this->crud->column('desc');
        $this->crud->column('complexity');
        $this->crud->column('color_fabric');
        $this->crud->column('backdrop');
        $this->crud->column('quantity');
        $this->crud->column('size');
        $this->crud->column('prepayment');
        $this->crud->column('price');
        $this->crud->column('delivery');
        $this->crud->orderBy('created_at');

        $stack = 'line';
        $name = 'tested';
        $model_function_name = 'sendTestMessageToTelegram';
        $position = 'beginning';
        $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position);
        $this->crud->addButton($stack, $name, 'model_function', 'checkTelegramMessage', $position); // possible types are: 'view', 'model_function'


        Widget::add(
          [
            'type'     => 'script',
            'content'  => 'assets/js/crud/custom-orders-crud.js',
            // optional
            'stack'    => 'after_scripts', // default is after_scripts
          ]
        );

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
            'client_name' => 'required',
            'desc' => 'required',
            'complexity' => 'required',
            'backdrop' => 'required',
            'quantity' => 'required',
            'size' => 'required',
            'prepayment' => 'required|max:8',
            'price' => 'required|max:8',
            'delivery' => 'required',
            'status_id' => 'required',
        ]);
        
        $this->crud->addField([
          'name' => 'created_at',
          'label' => 'Created at',
          'type' => 'datetime',
          'default' => now()
        ]);
        $this->crud->field('date_of_issue');
        $this->crud->addField([
          'name' => 'phone_number',
          'label' => 'Phone number',
          'type' => 'custom_phone',
          'prefix' => '+'
        ]);
        $this->crud->addField([
          'name' => 'client_name',
          'label' => 'Client name',
          'type' => 'text',
        ]);
        $this->crud->addField([
          'name' => 'desc',
          'label' => 'Description',
          'type' => 'textarea',
        ]);
        $this->crud->addField([
          'name' => 'complexity',
          'label' => 'Complexity',
          'type' => 'complexity',
          'hint' => 'Max. 50 characters'
        ]);
        $this->crud->field('color_fabric');
        $this->crud->field('backdrop');
        $this->crud->addField([
          'name'      => 'quantity', // The db column name
          'label'     => 'Quantity', // Table column heading
          'type'      => 'number',
          'decimals'  => 2,
          'limit'     => 2
        ]);
        $this->crud->field('size');
        $this->crud->addField([
          'name'      => '', // The db column name
          'label'     => 'Precurrency', // Table column heading
          'type'      => 'select',
          'name'      => 'prepayment_cur_id', // the column that contains the ID of that connected entity;
          'entity'    => 'prepayment_currency', // the method that defines the relationship in your Model
          'attribute' => 'symbol', // foreign key attribute that is shown to user
          'model'     => "App\Models\Currency", // foreign key model
          'wrapper' => ['class' => 'form-group col-sm-4'],
        ]);
        $this->crud->addField([
          'name'      => 'prepayment', // The db column name
          'label'     => 'Prepayment', // Table column heading
          'type'      => 'number',
          'wrapper' => ['class' => 'form-group col-sm-8'],
        ]);
        $this->crud->addField([
          'name'      => '', // The db column name
          'label'     => 'Currency', // Table column heading
          'type'      => 'select',
          'name'      => 'price_cur_id', // the column that contains the ID of that connected entity;
          'entity'    => 'price_currency', // the method that defines the relationship in your Model
          'attribute' => 'symbol', // foreign key attribute that is shown to user
          'model'     => "App\Models\Currency", // foreign key model
          'wrapper' => ['class' => 'form-group col-sm-4'],
        ]);
        $this->crud->addField([
          'name'      => 'price', // The db column name
          'label'     => 'Price', // Table column heading
          'type'      => 'number',
          'wrapper' => ['class' => 'form-group col-sm-8'],
        ]);
        $this->crud->addField([
          'label' => 'Delivery',
          'type' => 'text',
          'name' => 'delivery',
          'value' => 'Tashkent'
        ]);

        $this->crud->addField([   // Upload
          'name'      => 'photos',
          'label'     => 'Photos',
          'type'      => 'custom_upload_multiple',
          'upload'    => true,
          // 'disk'      => 'uploads', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
          // optional:
          // 'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
        ],);
        $this->crud->addField([
          // 1-n relationship
          'label'     => 'Artists', // Table column heading
          'type'      => 'custom_user_select',
          'name'      => 'user_id', // the column that contains the ID of that connected entity;
          'entity'    => 'users', // the method that defines the relationship in your Model
          'attribute' => 'name', // foreign key attribute that is shown to user
          'model'     => "App\Models\User", // foreign key model
        ]);

        $this->crud->addField([
          // 1-n relationship
          'label'     => 'Status', // Table column heading
          'type'      => 'custom_status_select',
          'name'      => 'status_id', // the column that contains the ID of that connected entity;
          'entity'    => 'hasStatus', // the method that defines the relationship in your Model
          'attribute' => 'name', // foreign key attribute that is shown to user
          'model'     => "App\Models\Status", // foreign key model
          'hint'      => 'Make sure stasuses are added before creating an order!',
          'events' => [
            'updating' => function ($entry) {
                OrderStatus::create([
                  'order_id' => $entry->id,
                  'status_id' => $entry->status_id
                ]);
            },
          ],
        ]);

        Widget::add()->type('script')->content('/assets/js/admin/forms/change-user.js');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        // Set telegram details
        $telegram_config = TelegramConfig::first();
        if ($telegram_config) {
          $this->telegram_details['token'] = $telegram_config->token;
          $this->telegram_details['chatId'] = $telegram_config->chat_id;
          $this->telegram_details['channel'] = substr($telegram_config->chat_id, 4);
  
          // Text to send to Telegram
          $this->telegram_details['text'] = 
            'Order ID: '.   $this->data['entry']->id."\n".
            'Date of issue: '.$this->data['entry']->date_of_issue."\n".
            'Client name: '.$this->data['entry']->client_name."\n".
            'Complexity: '. $this->data['entry']->complexity."\n".
            'Color fabric: '.$this->data['entry']->color_fabric."\n".
            'Backdrop: '.   $this->data['entry']->backdrop."\n".
            'Quantity: '.   $this->data['entry']->quantity."\n".
            'Size: '.       $this->data['entry']->size."\n".
            'Delivery: '.   $this->data['entry']->delivery
          ;
  
          // Send to Telegram and recive response
          $response = (empty($this->data['entry']->photos)) ? 
          $this->sendTextToTelegram():$this->sendTelegramWithPhotos($this->data['entry']->photos);
  
          $response = json_decode($response->body());
  
  
          // Store response in to DB
          $store_telegram_response = new TelegramInfo();
          $store_telegram_response->order_id = $this->data['entry']->id;
          $store_telegram_response->successful = $response->ok;
          $store_telegram_response->response_body = json_encode($response->result);
          $store_telegram_response->save();
  
          // Store telegram message link into orders table
          if ($response->ok) {
            $telegram_link = 'https://t.me/c/'.$this->telegram_details['channel'].'/'.$response->result->message_id;
            Orders::find($this->data['entry']->id)->update([
              'telegram_link' => $telegram_link
            ]);
          } 
        }

        // show a success message
        FacadesAlert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
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

    public function sendTextToTelegram()
    {
      $url = 'https://api.telegram.org/bot'.$this->telegram_details['token'].'/sendMessage';

      $arrayQuery = array(
        'chat_id' => $this->telegram_details['chatId'],
        'text' => $this->telegram_details['text']
      );		

      return Http::post($url, $arrayQuery);
    }

    public function sendTelegramWithPhotos($path_photos)
    {
      if (count($path_photos) == 1) return $this->sendTelegramWithSinglePhoto($path_photos[0]);

      $photos = [];
      foreach ($path_photos as $key => $value) {
        $storage_path = asset(Storage::disk('local')->url($value));
        if ($key == 0) {
          array_push($photos, [
            'type' => 'photo',
            'media' => $storage_path,
            'caption' => $this->telegram_details['text'],
          ]);
        } else {
          array_push($photos, [
            'type' => 'photo',
            'media' => $storage_path,
          ]);
        }
      }

      $url = 'https://api.telegram.org/bot'. $this->telegram_details['token'] .'/sendMediaGroup';

      $arrayQuery = array(
        'chat_id' => $this->telegram_details['chatId'],
        'media' => json_encode(array_values($photos))
      );		

      return Http::post($url, $arrayQuery);
    }

    public function sendTelegramWithSinglePhoto($photo)
    {
      $photo_path = asset(Storage::disk('local')->url($photo));

      $url = 'https://api.telegram.org/bot'.$this->telegram_details['token'].'/sendPhoto';

      $arrayQuery = array(
        'chat_id' => $this->telegram_details['chatId'],
        'caption' => $this->telegram_details['text'],
        'photo' => $photo_path
      );

      return Http::post($url, $arrayQuery);
    }
}
