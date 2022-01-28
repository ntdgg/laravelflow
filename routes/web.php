<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Index\IndexController::class, 'index'])->name('index.index.index');
Route::get('welcome', [App\Http\Controllers\Index\IndexController::class, 'welcome'])->name('index.index.welcome');

Route::get('news/index', [App\Http\Controllers\Index\NewsController::class, 'index'])->name('index.news.index');
Route::get('news/add', [App\Http\Controllers\Index\NewsController::class, 'add'])->name('index.news.add');
Route::get('news/view', [App\Http\Controllers\Index\NewsController::class, 'view'])->name('index.news.view');
Route::get('news/edit', [App\Http\Controllers\Index\NewsController::class, 'edit'])->name('index.news.edit');
