<?php

use App\Http\Controllers\TodoController;
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
Route::get('/task',       [TodoController::class, 'getAllTasks']);
Route::get('/task/fetch/{id}',       [TodoController::class, 'fetchTask']);
Route::post('/task',       [TodoController::class, 'insertTask']);
Route::put('/task',       [TodoController::class, 'updateTask']);
Route::delete('/task/del/{id}',     [TodoController::class, 'deleteTask']);
