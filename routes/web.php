<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\http\controllers\Api\SemproController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Auth::routes();
  
/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:mahasiswa'])->group(function () {
  
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/seminar/create', [SemproController::class, 'create'])->name('seminar.create');
    Route::post('/seminar', [SemproController::class, 'store'])->name('seminar.store');
});
  
/*------------------------------------------

--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:dosen'])->group(function () {
  
    Route::get('/dosen/home', [HomeController::class, 'dosenHome'])->name('dosen.home');
    Route::get('/beriNilai/{id}', [SemproController::class, 'beriNilai'])->name('beriNilai');
    Route::post('/simpan-nilai', [SemproController::class, 'simpanNilai'])->name('simpanNilai');
});
  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:koordinator'])->group(function () {
  
    Route::get('/koor/home', [HomeController::class, 'koorHome'])->name('koor.home');
});