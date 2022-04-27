<?php

use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){

    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/update', [UserController::class, 'update'])->name('api.user.update');
    Route::get('/all', [UserController::class, 'list'])->name('api.user.all');
    Route::get('/show/{id}',[UserController::class, 'show'])->name('api.user.show');
    Route::post('/remove/{id}',[UserController::class, 'remove'])->name('api.user.remove');

});


