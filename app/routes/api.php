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
        'prefix'     => 'api/v1/image',
        'middleware' => ['api'],
        'name'       => 'v1.image.'

    ],
    function () {
        Route::get('/{page?}', 'Index')
        ->where(['page' => '[0-9]+'])
        ->name('index');
    }
);
