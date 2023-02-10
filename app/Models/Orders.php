<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Orders extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
      'date_of_issue',
      'phone_number',
      'name',
      'desc',
      'complexity',
      'color_fabric',
      'backdrop',
      'quantity',
      'size',
      'prepayment',
      'price',
      'user_id',
      'delivery',
      'status_id',
      'media',
    ];

    public function statuses()
    {
        return $this->belongsToMany(Status::class, 'status_orders');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function hasStatus()
    {
      return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}
