<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; //WAJIB TAMBAHIN INI UNTUK SETIAP CONTROLLERNYA
use App\Http\Controllers\DestCategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EventController;
//ROUTING NANTI DISINI
//HTML nya taro di folder resources/views belakangnya dikasih ekstensi .blade.php (contoh homepage.blade.php)

Route::get('/', function () {
    return view('homepage');
});

// --- LOGIN FLOW ---
Route::get('/login', function () {
    return view('akun.login');
})->name('login');

Route::post('/login-proses', [AdminController::class, 'login']);

// --- PASSKEY 2 FLOW (Setelah Login) ---
Route::get('/verifikasi-login', [AdminController::class, 'showVerifikasiLogin']);
Route::post('/verifikasi-login-proses', [AdminController::class, 'prosesVerifikasiLogin']);

// --- REGISTER FLOW ---
Route::get('/register', function () {
    return view('akun.registrasiAkun');
});
Route::post('/register-proses', [AdminController::class, 'register']);

// Route untuk memproses data registrasi (POST)
Route::post('/register-proses', [AdminController::class, 'register']);


// Route Group Admin (Hanya bisa diakses jika sudah login)
Route::prefix('admin')->group(function () {
    // --- HOMEPAGE ADMIN (Hanya bisa akses jika sudah login penuh) ---
    Route::get('/Homepage', function () {
        // Cek keamanan sederhana (Middleware manual)
        if (!session()->has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        return view('admin.homepageAdmin');
    });

    // Menu Akun
    Route::get('/Account', [AdminController::class, 'showAccount']);
    
    // Delete Akun
    Route::delete('/delete-account', [AdminController::class, 'deleteAccount']);

    // HALAMAN EDIT PROFILE (GET)
    Route::get('/Edit-Profile', [AdminController::class, 'editProfile']);

    // PROSES UPDATE PROFILE (PUT)
    Route::put('/Update-Profile', [AdminController::class, 'updateProfile']);

    // Halaman List Event (GET)
    Route::get('/Event', [EventController::class, 'index']);

    // Halaman Detail Event (GET)
    // URL: /admin/event/detail/evt001
    Route::get('/Event/Detail/{id}', [EventController::class, 'tampilkanDetailEvent']);

    // HALAMAN ADD EVENT (GET)
    Route::get('/event/create', [EventController::class, 'addEvent']);

    // PROSES SIMPAN EVENT (POST)
    Route::post('/Event/store', [EventController::class, 'storeEventData']);
    
    // Route Delete (Sudah ada di jawaban sebelumnya, pastikan ada)
    Route::delete('/Event/Delete/{id}', [EventController::class, 'hapusEvent']);
});

// Route Logout (Bisa ditaruh di luar group admin)
Route::post('/logout', [AdminController::class, 'logout']);

// Route::get('/destinasi', [DestinationController::class, 'index']);
// Route::get('/event', [EventController::class, 'index']);
