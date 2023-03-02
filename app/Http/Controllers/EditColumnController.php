<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class EditColumnController extends Controller
{
    //
    public function update($id, Request $request)
    {
      $order = Orders::find($id);

      $data = $request->new_data;
      
      $order->update($data);

      $order_status = new OrderStatus();
      $order_status->order_id = $data['id'];
      $order_status->status_id = $data['status_id'];
      $order_status->save();

      return json_encode($order);
    }
}
