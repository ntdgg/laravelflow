<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Index\NewsController;
use App\Http\Controllers\Index\IndexController;
use Tpflow\Api;

Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('welcome', 'welcome');
    Route::get('login/{id}/{name}', 'login');
});

session(['uid' => 1]);
session(['role' => 213]);
Route::any('/wf/designapi/{act}/{flow_id?}', '\tpflow\Api@designapi');//工作流设计统一接口API
Route::any('/wf/wfapi', '\tpflow\Api@wfapi');//工作流前端管理统一接口

Route::any('/wf/wfdo', '\tpflow\Api@wfdo');//工作流审批统一接口

Route::controller(NewsController::class)->group(function () {
    Route::get('news/index', 'index');
    Route::any('news/add', 'add');
    Route::any('news/edit/{id}', 'edit');
    Route::any('news/view/{id}', 'view');
});
