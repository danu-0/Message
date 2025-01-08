<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PesanController;


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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me',                [AuthController::class, 'me']);
    Route::get('refresh',           [AuthController::class, 'refresh']);
    Route::get('logout',            [AuthController::class, 'logout']);
    Route::get('Users',             [AuthController::class, 'index']);

    Route::prefix('pesan')->group(function () {
        Route::get('/',             [PesanController::class, 'index']);
        Route::get('/{user_id}',    [PesanController::class, 'indexByUser']);
        Route::post('/',            [PesanController::class, 'create']);
    });
});
