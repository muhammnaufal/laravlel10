<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HakaksesController;
use App\Http\Controllers\BuatSuratController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\BebanAnggaranController;
use App\Http\Controllers\ManajemenSuratController;
use App\Models\BebanAnggaran;

// use App\Http\Controllers\DisposisiController;

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

Route::middleware(['guest'])->group(function () {

    Route::get('/',[LoginController::class,'index'])->name('index');
    Route::post('/login',[LoginController::class,'authenticate'])->name('login');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('user')->name('user.')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('show'); 
        Route::post('/create-update', [UserController::class, 'storeOrUpdate'])->name('create_update'); 
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit'); 
        Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('delete'); 
        Route::post('/reset/{id}', [UserController::class, 'reset'])->name('reset'); 
    });

    Route::prefix('profile')->name('profile.')->group(function() {
        Route::get('/{id}', [ProfileController::class, 'index'])->name('show'); 
        Route::post('/update/{id}', [ProfileController::class, 'update'])->name('update'); 
    });
    
    Route::prefix('surat')->name('surat.')->group(function() {
        Route::prefix('buat_surat')->name('buat_surat.')->group(function() {
            Route::get('/', [BuatSuratController::class, 'index'])->name('show'); 
            Route::post('/create', [BuatSuratController::class, 'store'])->name('create'); 
            Route::post('/api/fetchjabatan', [BuatSuratController::class, 'fetchjabatan'])->name('api-jabatan'); 
            Route::post('/api/fetchnip', [BuatSuratController::class, 'fetchnip'])->name('api-nip'); 
            Route::post('/pdf', [BuatSuratController::class, 'pdflink'])->name('pdf'); 
            Route::match(['get', 'post'], 'pdfview', [BuatSuratController::class, 'pdfview'])->name('pdfview');
        });
        Route::prefix('disposisi_surat')->name('manajemen_surat.')->group(function() {
            Route::get('/', [ManajemenSuratController::class, 'index'])->name('show'); 
            Route::post('/update', [ManajemenSuratController::class, 'update'])->name('update'); 
            Route::get('/detail/{id}', [ManajemenSuratController::class, 'detail'])->name('detail'); 
            Route::post('/change-e4/{id}', [ManajemenSuratController::class, 'e4'])->name('change_e4'); 
            Route::post('/change-e3/{id}', [ManajemenSuratController::class, 'e3'])->name('change_e3'); 
            Route::post('/change-e2/{id}', [ManajemenSuratController::class, 'e2'])->name('change_e2');
            Route::post('/arsip/{id}', [ManajemenSuratController::class, 'arsip'])->name('arsip');
            Route::get('/delete/{id}', [ManajemenSuratController::class, 'destroy'])->name('delete'); 
            Route::get('/download/{id}',  [ManajemenSuratController::class, 'download'])->name('download'); 
        });
        Route::prefix('arsip_surat')->name('arsip_surat.')->group(function() {
            Route::get('/', [ArsipSuratController::class, 'index'])->name('show'); 
        });
    });
    
    Route::prefix('master-data')->name('master_data.')->group(function() {

        Route::prefix('bidang')->name('bidang.')->group(function() {
            Route::get('/', [BidangController::class, 'index'])->name('show'); 
            Route::post('/create-update', [BidangController::class, 'storeOrUpdate'])->name('create_update'); 
            Route::get('/edit/{id}', [BidangController::class, 'edit'])->name('edit'); 
            Route::get('/delete/{id}', [BidangController::class, 'destroy'])->name('delete'); 
        });

        Route::prefix('jabatan')->name('jabatan.')->group(function() {
            Route::get('/', [JabatanController::class, 'index'])->name('show'); 
            Route::post('/create-update', [JabatanController::class, 'storeOrUpdate'])->name('create_update'); 
            Route::get('/edit/{id}', [JabatanController::class, 'edit'])->name('edit'); 
            Route::get('/delete/{id}', [JabatanController::class, 'destroy'])->name('delete'); 
        });

        Route::prefix('hak_akses')->name('hak_akses.')->group(function() {
            Route::get('/', [HakaksesController::class, 'index'])->name('show'); 
            Route::post('/create-update', [HakaksesController::class, 'storeOrUpdate'])->name('create_update'); 
            Route::get('/edit/{id}', [HakaksesController::class, 'edit'])->name('edit'); 
            Route::get('/delete/{id}', [HakaksesController::class, 'destroy'])->name('delete'); 
        });

        Route::prefix('lembaga_negara')->name('lembaga_negara.')->group(function() {
            Route::get('/', [BebanAnggaranController::class, 'index'])->name('show'); 
            Route::post('/create-update', [BebanAnggaranController::class, 'storeOrUpdate'])->name('create_update'); 
            Route::get('/edit/{id}', [BebanAnggaranController::class, 'edit'])->name('edit'); 
            Route::get('/delete/{id}', [BebanAnggaranController::class, 'destroy'])->name('delete'); 
        });

    });    
    
    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
});

