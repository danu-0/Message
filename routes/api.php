<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PesanController;
use App\Models\Contact;

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
Route::get('user',[AuthController::class,'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('me',                [AuthController::class, 'me']);
    Route::get('refresh',           [AuthController::class, 'refresh']);
    Route::get('logout',            [AuthController::class, 'logout']);

    Route::prefix('pesan')->group(function () {
        Route::get('',             [PesanController::class, 'index']);
        Route::get('/{id}',    [PesanController::class, 'indexById']);
        Route::get('/user/{id}',    [PesanController::class, 'indexByUserId']);
        Route::post('',            [PesanController::class, 'create']);
        Route::patch('/{id}', [PesanController::class, 'update']);
        Route::delete('/{id}', [PesanController::class, 'delete']);


    });

    Route::prefix('contact')->group(function () {
        Route::get('', [ContactController::class, 'index']);
        Route::get('/{id}', [ContactController::class, 'indexById']);
        Route::get('/user/{id}', [ContactController::class, 'indexByUserId']);
        Route::post('', [ContactController::class, 'create']);
        Route::delete('/{id}', [ContactController::class, 'delete']);
    });

    Route::prefix('notification')->group(function () {
        Route::get('', [NotificationController::class, 'index']);
        Route::get('/{id}', [NotificationController::class, 'indexById']);
        Route::get('/user/{id}', [NotificationController::class, 'indexByUserId']);
        Route::post('', [NotificationController::class, 'create']);
        Route::patch('/{id}', [NotificationController::class, 'update']);
        Route::delete('/{id}', [NotificationController::class, 'delete']);
    });
});
