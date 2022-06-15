<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "wf"], function () {
    Route::controller(LaravelFlow\Http\Controllers\Api::class)->group(function () {
        // 设计器接口
        Route::any('designapi/{act}/{flow_id?}', 'designapi');
        // 审批流程接口
        Route::any('wfdo/{act}/{wf_type?}/{wf_fid?}', 'wfdo');
        // 工作流前端管理统一接口
        Route::any('wfapi/{act?}', 'wfapi');
    });
});
