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
      'telegram_link',
      'archived',
      'created_at',
    ];

      
    protected $casts = ['photos' => 'array'];
    protected $translatable = ['name', 'options'];


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
    
    public function prepaymentCurrency()
    {
      return $this->belongsTo(Currency::class, 'prepayment_cur_id', 'id')->orderBy('currency_order');
    }

    public function priceCurrency()
    {
      return $this->belongsTo(Currency::class, 'price_cur_id', 'id')->orderBy('currency_order');
    }

    public function checkTelegramMessage()
    {
      return ($this->telegram_link) ? '<a href="'.$this->telegram_link.'" target="_blank" class="btn btn-sm btn-link"><i class="lab la-telegram-plane"></i></a>':'';
    }

    public function makeOrderArchived()
    {
      return (!$this->archived) ? '<a href="'.url(config('backpack.base.route_prefix').'/orders-make-archived/'.$this->id).'" class="btn btn-sm btn-link"><i class="las la-trash"></i></a>':'';
    }

    public function setPhotosAttribute($value)
    {
        $attribute_name = "photos";
        $disk = "public";
        $destination_path = "photos";
    
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path)
    {
        $originalModelValue = $this->getOriginal()[$attribute_name] ?? [];

        if (! is_array($originalModelValue)) {
            $attribute_value = json_decode($originalModelValue, true) ?? [];
        } else {
            $attribute_value = $originalModelValue;
        }

        $files_to_clear = request()->get('clear_'.$attribute_name);

        // if a file has been marked for removal,
        // delete it from the disk and from the db
        if ($files_to_clear) {
            foreach ($files_to_clear as $key => $filename) {
                Storage::disk($disk)->delete($filename);
                $attribute_value = Arr::where($attribute_value, function ($value, $key) use ($filename) {
                    return $value != $filename;
                });
            }
        }

        // if a new file is uploaded, store it on disk and its filename in the database
        if (request()->hasFile($attribute_name)) {
            foreach (request()->file($attribute_name) as $key => $file) {
                if ($file->isValid()) {
                    // 1. Generate a new file name
                    $new_file_name = time().$key.' '.random_int(1, 9999).$file->getClientOriginalName();

                    // 2. Move the new file to the correct path
                    $file_path = $file->storeAs($destination_path, $new_file_name, $disk);

                    // 3. Add the public path to the database
                    $attribute_value[] = $file_path;
                }
            }
        }

        $this->attributes[$attribute_name] = json_encode($attribute_value);
    }
    
}
