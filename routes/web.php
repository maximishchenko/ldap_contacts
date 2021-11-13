<?php

// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::redirect('/', '/company', 301);
//Route::redirect('/', '/company', 301);
Route::get('/', [Controller::class, 'index']);
Route::get('/sync', [Controller::class, 'sync']);
Route::get('/subscribe', [Controller::class, 'subscribe'])->name('subscribe');
Route::get('/autocomplete', [Controller::class, 'autocomplete'])->name('autocomplete');
Route::get('/excel/{slug}', [Controller::class, 'companyToExcel']);
Route::get('/{slug}', [Controller::class, 'index']);
