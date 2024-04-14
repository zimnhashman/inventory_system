<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use Illuminate\Routing\Route;

Route::middleware('auth:api')->group(function () {
    Route::resource('items', ItemController::class);
    Route::resource('categories', CategoryController::class);
});

