<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Currency extends Model
{
  use HasFactory, CrudTrait;

  protected $fillable = [
    'name',
    'short_name',
    'symbol',
    'currency_order'
  ];

}
