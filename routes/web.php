<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::post('/items/upload-image', [ItemController::class, 'uploadImage'])->name('items.upload-image');


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
