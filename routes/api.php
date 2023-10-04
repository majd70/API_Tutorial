<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//here all api must be authenthicated with laravel jwt
Route::group(['middleware'=>'jwt.verify'],function(){
    Route::get('/all-categories',[CategoriesController::class ,'index']);
    Route::get('/category/{id}',[CategoriesController::class ,'show']);
    Route::post('/categories',[CategoriesController::class ,'store']);
    Route::put('/categories/{id}',[CategoriesController::class ,'update']);
    Route::delete('/category/{id}',[CategoriesController::class ,'destroy']);



});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


