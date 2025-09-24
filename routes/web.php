<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('ok', 200);
});
