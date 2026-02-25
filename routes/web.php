<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
   echo 'Proyecto Laravel para TFG de 2 de desarrollo de aplicaciones web.';
});
