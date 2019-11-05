<?php

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

Route::get('uibot/jobs', 'UiBotController@jobUrls');

Route::post('lagou/pour', 'LagouController@pour')->middleware('cors');
