<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Index\NewsController;
use App\Http\Controllers\Index\IndexController;

Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('welcome', 'welcome');
    Route::get('login/{id}/{name}', 'login');
});


Route::controller(NewsController::class)->group(function () {
    Route::get('news/index', 'index');
    Route::any('news/add', 'add');
    Route::any('news/edit/{id}', 'edit');
    Route::any('news/view/{id}', 'view');
});
