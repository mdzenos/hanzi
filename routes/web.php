<?php

use App\Http\Controllers\Dict;
use App\Http\Controllers\SyncDict;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/sync', [SyncDict::class, 'sync']);

Route::get('/', [Dict::class, 'search'])->name('search');
