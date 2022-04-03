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

    Route::post('/auth/logout', [UserController::class, 'logout' ] );

    Route::prefix('user')->group(function(){
        Route::get('/list', [UserController::class, 'index' ]);
        Route::get('/{id}', [UserController::class, 'show' ]);
        Route::put('/{id}', [UserController::class, 'update' ]);
        Route::delete('/{id}', [UserController::class, 'destroy' ]);
    });

    Route::prefix('item')->group(function(){
        /**
        Route::get('/list', [ItemController::class, 'index' ]);
        Route::get('/{id}', [ItemController::class, 'show' ]);
        Route::put('/{id}', [ItemController::class, 'update' ]);
        Route::delete('/{id}', [ItemController::class, 'destroy' ]);
        **/
    });

});
