<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class TelegramConfig extends Model
{
    use HasFactory,CrudTrait;

    protected $fillable = [
      'token',
      'chat_id',
    ];
}
