<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class OrderStatus extends Model
{
  use HasFactory, CrudTrait;

  protected $fillable = [
    'order_id',
    'status_id'
  ];

  public function orders()
  {
    return $this->belongsTo(Orders::class, 'order_id', 'id');
  }

  public function status()
  {
    return $this->belongsTo(Status::class, 'status_id', 'id');
  }
}
