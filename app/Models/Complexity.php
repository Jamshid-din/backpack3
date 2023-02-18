<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Complexity extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
      'char',
      'currency_id',
      'value',
      'complexity_order',
    ];

    public function currency()
    {
      return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
