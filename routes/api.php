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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [UserController::class, 'logout']);
    Route::put('/update', [UserController::class, 'update'])->name('api.user.update');
    Route::put('/update-user/{id}', [UserController::class, 'updateSelectUser'])->name('api.user.update-select');
    Route::post('/create-user', [UserController::class, 'createUser'])->name('api.user.create');
    Route::post('/image-user', [UserController::class, 'imageUser'])->name('api.user.update.image');
    Route::get('/list', [UserController::class, 'list'])->name('api.user.all');
    Route::get('/show/{id}',[UserController::class, 'show'])->name('api.user.show');
    Route::delete('/delete/{id}',[UserController::class, 'delete'])->name('api.user.remove');
});





