<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Index\NewsController;


Route::get('/', [App\Http\Controllers\Index\IndexController::class, 'index'])->name('index.index.index');
Route::get('welcome', [App\Http\Controllers\Index\IndexController::class, 'welcome'])->name('index.index.welcome');


Route::get('login/{id}/{name}', [App\Http\Controllers\Index\IndexController::class, 'login'])->name('index.index.login');

Route::get('news/index', [NewsController::class, 'index'])->name('index.news.index');
Route::get('news/add', [NewsController::class, 'add'])->name('index.news.add');
Route::get('news/view/{id}', [NewsController::class, 'view'])->name('index.news.view');
Route::any('news/edit/{id}', [NewsController::class, 'edit'])->name('index.news.edit');
