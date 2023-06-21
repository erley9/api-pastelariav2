<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
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

Route::post('/login', [AuthController::class,'auth']);
Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:api');


Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('/client', ClientController::class);
    Route::apiResource('/product', ProductController::class);
    Route::apiResource('/order', OrderController::class, array('only' => array('index', 'store', 'update')));
    Route::get('/order/{client}', [OrderController::class,'show']);
    Route::put('/order', [OrderController::class,'update']);
    Route::delete('/order/{client}', [OrderController::class,'destroy']);
});

