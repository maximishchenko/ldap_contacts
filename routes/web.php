<?php

// use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PhonebookController;

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

Route::get('/', function () {
   return view('dashboard');
})->name('home');

// Route::redirect('/', '/company', 301);
// Route::redirect('/', '/phonebook', 301);

Route::get('/sync', [Controller::class, 'sync']);

Route::get('/phonebook', [PhonebookController::class, 'index'])->name('phonebook');
Route::get('/phonebook/subscribe', [Controller::class, 'subscribe'])->name('subscribe');
Route::get('/phonebook/autocomplete', [PhonebookController::class, 'autocomplete'])->name('autocomplete');
Route::get('/phonebook/excel/{slug}', [PhonebookController::class, 'companyToExcel']);
Route::get('/phonebook/{slug}', [PhonebookController::class, 'index']);


Route::get('/company', [CompanyController::class, 'index'])->name('company');
Route::get('/company/{slug}', [CompanyController::class, 'show'])->name('company.show');
