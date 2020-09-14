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

Route::get('actions', [
    'uses'  =>  'ActionController@get',
    'as'    =>  'action.get'
]);

Route::post('action', [
    'uses'  =>  'ActionController@insert',
    'as'    =>  'action.post'
]);

Route::delete('actions', [
    'uses'  =>  'ActionController@delete',
    'as'    =>  'action.delete'
]);

Route::get('check', [
    'uses'  =>  'ActionController@checkWin',
    'as'    =>  'action.checkwin'
]);
