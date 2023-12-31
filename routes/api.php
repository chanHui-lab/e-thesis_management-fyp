<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/testvuedata', [App\Http\Controllers\Admin\FormController::class, 'fetchdata']);

Route::get('/download/{filePath}', [App\Http\Controllers\Admin\FormController::class, 'downloadVue'])->where('filePath', '.*');

Route::get('/student/dashreminder', [App\Http\Controllers\Admin\DashboardController::class, 'fetchReminderData']);

Route::get('/students', [App\Http\Controllers\Admin\DashboardController::class, 'indexStu']);
