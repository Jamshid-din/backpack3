<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;


class Orders extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
      'photos',
      'date_of_issue',
      'phone_number',
      'client_name',
      'desc',
      'complexity',
      'color_fabric',
      'backdrop',
      'quantity',
      'size',
      'prepayment_cur_id',
      'prepayment',
      'price_cur_id',
      'price',
      'user_id',
      'delivery',
      'status_id',
      'created_at',
    ];

      
    protected $casts = ['photos' => 'array'];


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
      return $this->belongsTo(Status::class, 'status_id', 'id')->orderBy('status_order', 'desc');
    }
    
    public function prepayment_currency()
    {
      return $this->belongsTo(Currency::class, 'prepayment_cur_id', 'id')->orderBy('currency_order');
    }

    public function price_currency()
    {
      return $this->belongsTo(Currency::class, 'price_cur_id', 'id')->orderBy('currency_order');
    }

    public function setPhotosAttribute($value)
    {
        $attribute_name = "photos";
        $disk = "public";
        $destination_path = "photos";
    
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
    
}
