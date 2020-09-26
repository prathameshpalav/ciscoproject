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

Route::group(['prefix' => 'routers', 'middleware' => 'auth:api'], function() {
    Route::get('/{ip}', 'RouterController@show')->name('router.show');
    Route::get('/', 'RouterController@list')->name('router.list');    
    Route::post('/', 'RouterController@insert')->name('router.insert');
    Route::post('/{ip}', 'RouterController@update')->name('router.update');
    Route::delete('/{ip}', 'RouterController@delete')->name('router.delete');
});
