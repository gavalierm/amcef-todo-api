<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

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

Route::middleware('guest')->prefix('auth')->group(function(){
    Route::post('/register', [UserController::class, 'register' ] );
    Route::post('/login', [UserController::class, 'login' ] );
});

Route::middleware('auth:sanctum')->group(function(){

    Route::get('/user', [UserController::class, 'index' ]);
    Route::get('/user/{id}', [UserController::class, 'show' ]);
    Route::put('/user/{id}', [UserController::class, 'update' ]);
    Route::delete('/user/{id}', [UserController::class, 'destroy' ]);

    Route::get('/register', [UserController::class, 'register' ] );
    Route::get('/login', [UserController::class, 'login' ] );
    Route::get('/login', [UserController::class, 'login' ] );

});
