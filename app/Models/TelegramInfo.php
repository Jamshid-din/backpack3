<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramInfo extends Model
{
    use HasFactory;

    protected $fillable = [
      'successful',
      'order_id',
      'response_body'
    ];
}
