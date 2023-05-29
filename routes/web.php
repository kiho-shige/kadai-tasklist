<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;

Route::get('/', [MessagesController::class, 'index']);
Route::resource('messages', MessagesController::class);