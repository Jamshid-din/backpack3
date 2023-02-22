<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class EditColumnController extends Controller
{
    //
    public function update($id, Request $request)
    {
      $order = Orders::find($id);

      $data = $request->new_data;
      
      $order->update($data);

      return json_encode($order);
    }
}
