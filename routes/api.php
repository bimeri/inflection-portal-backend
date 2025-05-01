<?php

use App\Http\Controllers\SuccessfulEmailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/emails', [SuccessfulEmailController::class, 'store']);
    Route::get('/emails/{id}', [SuccessfulEmailController::class, 'getById']);
    Route::put('/emails/{id}', [SuccessfulEmailController::class, 'update']);
    Route::delete('/emails/{id}', [SuccessfulEmailController::class, 'destroy']);
    Route::get('/emails', [SuccessfulEmailController::class, 'index']);
});
