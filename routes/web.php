<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\http\controllers\Api\SemproController;
use App\Http\Controllers\Api\SemhasController;
use App\Http\Controllers\Api\SidangController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'login']);
Route::get('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::post('/create-signature', [HomeController::class, 'saveSignature'])->name('saveSignature');

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
    Route::get('/seminar_hasil', [SemhasController::class, 'index']);
    Route::post('/seminar_hasil', [SemhasController::class, 'store']);

    Route::get('/semhas/create', [SemhasController::class, 'create'])->name('semhas.create');
    Route::post('/semhas', [SemhasController::class, 'store'])->name('semhas.store');

    Route::get('/sidang/create', [SidangController::class, 'create'])->name('sidang.create');
    Route::post('/sidang', [SidangController::class, 'store'])->name('sidang.store');
});
  
/*------------------------------------------

--------------------------------------------
Dosen Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:dosen'])->group(function () {
  
    Route::get('/dosen/home', [HomeController::class, 'dosenHome'])->name('dosen.home');

    Route::get('/beriNilai/{id}', [SemproController::class, 'beriNilai'])->name('beriNilai');
    Route::post('/simpan-nilai', [SemproController::class, 'simpanNilai'])->name('simpanNilai');
    Route::get('/dosen/pen05', [SemproController::class, 'pen05Home'])->name('dosen.pen05Home');
    Route::get('/dosen/pen05/{id}', [SemproController::class, 'pen05'])->name('dosen.pen05');
    Route::post('/send-data-to-coordinator', [SemproController::class, 'sendDataToCoordinator'])->name('sendDataToCoordinator');

    Route::get('/beriNilaiSemhas/{id}', [SemhasController::class, 'beriNilai'])->name('beriNilaiSemhas');
    Route::post('/simpan-nilaiSemhas', [SemhasController::class, 'simpanNilai'])->name('simpanNilaiSemhas');
    Route::get('/dosen/pen09', [SemhasController::class, 'pen09Home'])->name('dosen.pen09Home');
    Route::get('/dosen/pen09/{id}', [SemhasController::class, 'pen09'])->name('dosen.pen09');
    Route::post('/send-data-semhas', [SemhasController::class, 'sendDataToCoordinator'])->name('sendDataSemhas');

    Route::get('/beriNilaiSidang/{id}', [SidangController::class, 'beriNilai'])->name('beriNilaiSidang');
    Route::post('/simpan-nilaiSidang', [SidangController::class, 'simpanNilai'])->name('simpanNilaiSidang');
    Route::get('/dosen/pen14', [SidangController::class, 'pen14Home'])->name('dosen.pen14Home');
    Route::get('/dosen/pen14/{id}', [SidangController::class, 'pen14'])->name('dosen.pen14');
    Route::post('/send-data-sidang', [SidangController::class, 'sendDataToCoordinator'])->name('sendDataSidang');
});
  
/*------------------------------------------
--------------------------------------------
Koor Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:koordinator'])->group(function () {
  
    Route::get('/koor/home', [HomeController::class, 'koorHome'])->name('koor.home');
    Route::get('/rekapNilai/{id}', [SemproController::class, 'rekapNilai'])->name('rekapNilai');
    Route::get('/sempro/export-pdf/{id}', [SemproController::class, 'exportPDF'])->name('export.pdf');
    Route::get('/koor/preview-pdf', [SemproController::class, 'previewPdf'])->name('koor.preview_pdf');



    Route::get('/rekapNilaiSemhas/{id}', [SemhasController::class, 'rekapNilai'])->name('rekapNilaiSemhas');
    Route::get('/semhas/export-pdf/{id}', [SemhasController::class, 'exportPDF'])->name('export.pdfSemhas');

    Route::get('/rekapNilaiSidang/{id}', [SidangController::class, 'rekapNilai'])->name('rekapNilaiSidang');
    Route::get('/sidang/export-pdf/{id}', [SidangController::class, 'exportPDF'])->name('export.pdfSidang');
});