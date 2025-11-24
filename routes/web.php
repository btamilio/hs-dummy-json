<?php

use Illuminate\Support\Facades\Route;
use Btamilio\HsDummyJson\Http\Controllers\SearchController;

 
Route::get('/hs-dummy-json/search', SearchController::class);
