<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api']], function () {
    // apps listesi
    Route::group(['prefix' => 'apps'], function () {
        Route::post('', [AppController::class, 'store']);
        Route::get('', [AppController::class, 'index']);
    });
    Route::group(['prefix' => 'devices'], function () {
        Route::post('register', [DeviceController::class, 'register']);
        Route::get('{id}', [DeviceController::class, 'show'])->where('id', '[0-9]+');
        Route::get('', [DeviceController::class, 'index']);
    });

    // token kontrolü yapılıyor
    Route::middleware(['CheckToken'])->group(function () {
        Route::group(['prefix' => 'purchase'], function () {
            Route::post('check', [PurchaseController::class, 'check']);
            Route::post('', [PurchaseController::class, 'index']);
        });
    });
    // uygulamaların günlük raporu
    Route::get('reports', [ReportController::class, 'index']);
});
