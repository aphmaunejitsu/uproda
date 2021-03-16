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

Route::group(
    [
        'namespace'  => 'Api\V1\Image',
        'prefix'     => 'v1/image',
    ],
    function () {
        Route::get('/', 'Index')->name('v1.image.index');
        Route::get('/{basename}', 'Detail')->name('v1.image.detail');
    }
);
