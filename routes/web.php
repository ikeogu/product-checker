<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
   return ['status' => 'API is running'];
});


