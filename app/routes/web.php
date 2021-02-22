<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'namespace'  => 'Api\V1\Image',
        'middleware' => ['api'],
        'name'       => 'front.'

    ],
    function () {
        Route::get('/about', function () {
            return view('welcome');
        })->name('about');

        Route::get('/image/{hash}', function () {
            return view('welcome');
        })->name('image');

        Route::get('/{page?}', function () {
            return view('welcome');
        })->name('top');
    }
);
