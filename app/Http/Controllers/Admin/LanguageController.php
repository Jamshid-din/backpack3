<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class LanguageController extends CrudController
{
    //
    public function switchLanguage($locale)
    {
      App::setLocale($locale);
      session()->put('locale', $locale);

      return redirect()->back();
    }
}
