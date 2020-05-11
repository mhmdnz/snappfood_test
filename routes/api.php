<?php

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

Route::get('/employee/{employee}', 'EmployeeController@get');
Route::post('/employee', 'EmployeeController@create');


Route::post('/call/start', 'CallController@start');
Route::post('/call/end', 'CallController@end');
Route::get('/call/answer/{employee}', 'CallController@answer');

