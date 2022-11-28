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

Route::get(
    '/',
    function () {
        return view('welcome');
    }
)->name('top');

Route::get(
    '/about',
    function () {
        return view('welcome');
    }
)->name('about');

Route::get(
    '/image/{hash}',
    function () {
        return view('welcome');
    }
)->where('hash', '[0-9a-zA-Z]{8}')
  ->name('image');
