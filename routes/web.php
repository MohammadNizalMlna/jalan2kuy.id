<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\DestCategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EventController;

//Note:
//Method GET digunakan untuk mengambil atau menampilkan data/halaman. Tidak mengubah data di server.
//Method POST digunakan untuk mengirim data baru ke server (biasanya dari form).
//Method PUT atau PATCH digunakan untuk memperbarui (update) data yang sudah ada secara keseluruhan.
//Method DELETE digunakan untuk menghapus data dari server.

//route halaman homepage (Method: GET)
Route::get('/', function () {
    return view('homepage');
});

//Alur Login (Start)
//route halaman login (Method: GET)
Route::get('/login', function () {
    return view('akun.login');
})->name('login');

//route proses login (Method: POST)
Route::post('/login-proses', [AdminController::class, 'login']);

//route halaman passkey (Method: GET)
Route::get('/verifikasi-login', [AdminController::class, 'showVerifikasiLogin']);

//route proses verifikasi passkey (Method: POST)
Route::post('/verifikasi-login-proses', [AdminController::class, 'prosesVerifikasiLogin']);
//Alur Login (End)

//Alur Register Akun Admin (Start)
//route halaman register akun admin (Method: GET)
Route::get('/register', function () {
    return view('akun.registrasiAkun');
});

//route proses registrasi akun admin (Method: POST)
Route::post('/register-proses', [AdminController::class, 'register']);
//Alur Register Akun Admin (End)

//Alur Destination (User Biasa) (Start)
//route halaman Utama Destination (Method: GET)
Route::get('/Destination', [DestCategoryController::class, 'tampilCategory']);

//route halaman Kategori Destination (Method: GET)
Route::get('/Destination/Category', [DestCategoryController::class, 'category']);

//route halaman detail destination (Method: GET)
Route::get('/Destination/Detail/{id}', [DestinationController::class, 'tampilkanDetailDestination']);
//Alur Destination (User Biasa) (End)

//Alur Event (User Biasa) (Start)
//route halaman Utama Event (Method: GET)
Route::get('/Event', [EventController::class, 'tampilEvent']);

//route halaman detail event (Method: GET)
Route::get('/Event/Detail/{id}', [EventController::class, 'tampilkanDetailEvent']);
//Alur Event (User Biasa) (End)

//route halaman Utama gallery (Method: GET)
Route::get('/Gallery', [DestinationController::class, 'tampilGaleri']);

//route proses logout (Method: POST)
Route::post('/logout', [AdminController::class, 'logout']);

////===ROUTE-ROUTE DIBAWAH INI KHUSUS ADMIN ONLY===

//route group admin (Hanya bisa diakses jika sudah login)
Route::prefix('admin')->group(function () {
    //route halaman homepage admin (Method: GET)
    Route::get('/Homepage', function () {
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!session()->has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        return view('admin.homepageAdmin');
    });

    //Alur Destination (User Admin) (Start)
    //route halaman Utama Destination Admin (Method: GET)
    Route::get('/Destination', [DestCategoryController::class, 'tampilCategoryAdmin']);

    //route halaman Kategori Destination admin (Method: GET)
    Route::get('/Destination/Category', [DestCategoryController::class, 'categoryAdmin']);

    //route halaman detail destination admin (Method: GET)
    Route::get('/Destination/Detail/{id}', [DestinationController::class, 'tampilkanDetailDestinationAdmin']);

    //route untuk menampilkan Form addDestination admin (Method: GET)
    Route::get('Destination/AddDestination', [DestinationController::class, 'addDestination']);

    //Route untuk menyimpan Data addDestination ke Database admin (Method: POST)
    Route::post('/Destination/Store', [DestinationController::class, 'storeDestinationData']);

    //route halaman form edit destination admin (Method: GET)
    Route::get('/Destination/Edit/{id}', [DestinationController::class, 'tampilFormEditDestination']);

    //route untuk proses simpan update destination admin (Method: PUT)
    Route::put('/Destination/Update/{id}', [DestinationController::class, 'editDestination']);

    //route untuk proes delete destination admin (Method: DELETE)
    Route::delete('/Destination/Delete/{id}', [DestinationController::class, 'deleteDestination']);
    //Alur Destination (User Admin) (End)

    //Alur Event (User Admin) (Start)
    //route halaman Utama Event Admin (Method: GET)
    Route::get('/Event', [EventController::class, 'tampilEventAdmin']);

    //route halaman detail event admin (Method: GET)
    Route::get('/Event/Detail/{id}', [EventController::class, 'tampilkanDetailEventAdmin']);

    //route halaman form add event admin (Method: GET)
    Route::get('/event/create', [EventController::class, 'addEvent']);

    //Route untuk menyimpan Data addEvent ke Database admin (Method: POST)
    Route::post('/Event/store', [EventController::class, 'storeEventData']);

    //route halaman form edit event admin (Method: GET)
    Route::get('/Event/Edit/{id}', [EventController::class, 'tampilFormEditEvent']);

    //route untuk proses simpan update event admin (Method: PUT)
    Route::put('/Event/Update/{id}', [EventController::class, 'editEvent']);

    //route untuk proes delete event admin (Method: DELETE)
    Route::delete('/Event/Delete/{id}', [EventController::class, 'hapusEvent']);
    //Alur Event (User Admin) (End)

    //route halaman Utama gallery Admin (Method: GET)
    Route::get('/Gallery', [DestinationController::class, 'tampilGaleriAdmin']);

    //Alur account (User Admin) (Start)
    //route halaman Utama account Admin (Method: GET)
    Route::get('/Account', [AdminController::class, 'showAccount']);

    //route halaman form edit account admin (Method: GET)
    Route::get('/Edit-Profile', [AdminController::class, 'tampilFormEditProfile']);

    //route untuk proses simpan update account admin (Method: PUT)
    Route::put('/Update-Profile', [AdminController::class, 'editProfile']);
    
    //route untuk proes delete account admin (Method: DELETE)
    Route::delete('/delete-account', [AdminController::class, 'deleteAccount']);
    //Alur account (User Admin) (End)
});