<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AdminController;

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

//API routes

Route::post('registration', [ApiController::class, 'registration']);
Route::post('login', [ApiController::class, 'login']);
Route::group([
    "middleware" => 'auth:api'
], function () {
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('users', [AdminController::class, 'getAll']);
    Route::post('admin/add', [AdminController::class, 'addUser']);
    Route::post('admin/delete', [AdminController::class, 'delete']);
    Route::post('update', [ApiController::class, 'update']);
});
