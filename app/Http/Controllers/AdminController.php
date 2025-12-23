<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use \App\Models\Event;
use \App\Models\Destination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller { //penamaan controller menggunakan huruf kapital pada awal masing-masing kata
    public function login(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        $usernameInput = $request->input('username'); //penamaan variabel diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        $passwordInput = $request->input('password');

        //query
        $usernameAdmin = Admin::where('username', $usernameInput)->first();

        if ($usernameAdmin) {
            if (Hash::Check($passwordInput, $usernameAdmin->password)) { // Cek Password apakah sama dengan yang disimpan didalam database atau tidak
                Session::put('temp_admin_id', $usernameAdmin->adminID); //simpan sementara adminID didalam temp_admin_id untuk dipakai pada verifikasi passkey
                Session::put('temp_admin_name', $usernameAdmin->name); //simpan sementara name didalam temp_admin_name untuk dipakai pada verifikasi passkey
                
                //Redirect ke URL halaman Passkey (/verifikasi-login)
                return redirect('/verifikasi-login');
            }
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function showVerifikasiLogin() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum 
        if (!Session::has('temp_admin_id')) { 
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        //redirect ke halaman passkey
        return view('akun.passkey');
    }

    public function prosesVerifikasiLogin(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        $inputPassKey = $request->input('passkey_code'); //simpan passkey yang diinputkan user kedalam $inputPassKey
        $passKey = '123456'; //PassKey yang valid (dibuat statis)

        //cek apakah passkey yang diinputkan sesuai atau tidak
        if ($inputPassKey === $passKey) { //jika passkey yang diinputkan sesuai
            $adminID = Session::get('temp_admin_id'); //ambil temp_admin_id dari session sementara, masukkan kedalam $adminID
            $adminName = Session::get('temp_admin_name'); //ambil temp_admin_name dari session sementara, masukkan kedalam $adminName

            //buat session yang resmi (query)
            Session::put('admin_id', $adminID); //perbarui admin_id pada session menggunakan $adminID
            Session::put('admin_name', $adminName); //perbarui admin_name pada session menggunakan $adminName

            //hapus session sementara (query)
            Session::forget('temp_admin_id');
            Session::forget('temp_admin_name');

            //Redirect ke URL homepage admin (admin/Homepage)
            return redirect('admin/Homepage');
        } else { //jika salah, Kembali ke halaman passkey dengan pesan error
            return back()->with('error', 'Passkey salah, silakan coba lagi.');
        }
    }

    public function register(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //validasi input
        $request->validate([
            'name' => 'required',
            'username' => 'required|alpha_num|unique:admin,username', 
            'email' => 'required|email|unique:admin,email',
            'gender' => 'required',
            'password' => [
                'required', 
                'confirmed', //passowrd yang diinputkan harus sesuai dengan yang diinputkan di form input ('password_confirmation')
                'min:8', //password minimal 8 karakter
                'regex:/[A-Z]/', //password harus ada huruf besar (minimal 1)
                'regex:/[0-9]/', //password harus ada angka (minimal 1)
                'regex:/[@$!%*#?&]/', //password harus ada simbol (minimal 1)
            ],
        ], [
            //Pesan Error Custom
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka (tanpa simbol).',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.regex'     => 'Password harus mengandung setidaknya 1 huruf besar, 1 angka, dan 1 simbol (@ $ ! % * # ? &).',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        //memulai transaction ke database
        DB::beginTransaction();

        try {
            //generate adminID baru (ID-ID terdiri dari 6 karakter dengan 3 karakter pertama adalah alfabet dan 3 karakter sisanya adalah angka (mengurut))
            $lastAdmin = Admin::orderBy('adminID', 'desc')->lockForUpdate()->first(); //ambil adminID terakhir yang ada pada tabel admin di database
            $newAdminID = 'adm001'; //jika tidak ditemukan adminID terakhir, gunakan $newAdminID

            if ($lastAdmin) { //jika ditemukan adminID terakhir, maka generate adminID baru
                $lastID = $lastAdmin->adminID;
                $number = (int) substr($lastID, 3);
                $number++;
                $newAdminID = 'adm' . sprintf("%03d", $number);
            }

            //buat objek Admin dan simpan datanya ke database
            $admin = new Admin();
            $admin->adminID = $newAdminID;
            $admin->name = $request->input('name');
            $admin->username = $request->input('username');      
            $admin->password = Hash::make($request->input('password')); //password disimpan dalam bentuk hash
            $admin->email = $request->input('email');
            $admin->gender = filter_var($request->input('gender'), FILTER_VALIDATE_BOOLEAN);
            $admin->save(); 

            //commit transaction ke database
            DB::commit();
            //Redirect ke URL login (/login) dengan pesan sukses
            return redirect('/login')->with('success', 'Registrasi Berhasil! Silakan Login.');
        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();
            //jika salah, Kembali ke halaman register dengan pesan error
            return back()->with('error', 'Gagal register : ' . $e->getMessage())->withInput();
        }
    }

    //===FUNCTION-FUNCTION DIBAWAH INI KHUSUS ADMIN ONLY===

    public function logout() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        Session::flush(); //Hapus semua data session
        
        //Redirect ke halaman homepage (/)
        return redirect('/')->with('success', 'Berhasil Logout.');
    }

    public function showAccount() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum 
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        
        //query
        $adminID = Session::get('admin_id'); //ambil adminID dari session

        //query
        $admin = Admin::find($adminID); //Cari data admin lengkap bedasarkan adminID yang diambil pada database

        //Kirim data $admin ke view accountAdmin
        return view('akun.accountAdmin', ['admin' => $admin]);
    }

    public function tampilFormEditProfile() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum 
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //query
        $adminID = Session::get('admin_id'); //ambil adminID dari session

        //query
        $admin = Admin::find($adminID); //Cari data admin lengkap bedasarkan adminID yang diambil pada database

        //kirim data $admin ke view editAkun
        return view('akun.editAkun', ['admin' => $admin]);
    }

    public function editProfile(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum 
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //query
        $adminID = Session::get('admin_id'); //ambil adminID dari session

        //query
        $admin = Admin::find($adminID); //Cari data admin lengkap bedasarkan adminID yang diambil pada database

        //Validasi Input
        $request->validate([
            'name' => 'required',
            'username' => 'required|alpha_num|unique:admin,username,' . $adminID . ',adminID',
            'email' => 'required|email|unique:admin,email,' . $adminID . ',adminID',
            'gender' => 'required',
            'password' => [
                'nullable', 
                'min:8', //password minimal 8 karakter
                'regex:/[A-Z]/', //password harus ada huruf besar (minimal 1)
                'regex:/[0-9]/', //password harus ada angka (minimal 1)
                'regex:/[@$!%*#?&]/', //password harus ada simbol (minimal 1)
            ],
        ], [
            //Pesan Error Custom
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka (tanpa simbol).',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.regex'     => 'Password harus mengandung setidaknya 1 huruf besar, 1 angka, dan 1 simbol (@ $ ! % * # ? &).',
        ]);

        //Update Data dari input yang sudah divalidasi dan simpan datanya ke database
        $admin->name = $request->input('name');
        $admin->username = $request->input('username');
        $admin->email = $request->input('email');
        $admin->gender = filter_var($request->input('gender'), FILTER_VALIDATE_BOOLEAN);
        //Cek apakah user mengisi password baru
        if ($request->filled('password')) { //jika user mengisi password baru
            $admin->password = Hash::make($request->input('password')); //simpan password baru dalam bentuk hash
        }
        $admin->save();

        //Update admin_name pada session  (jika nama berubah)
        Session::put('admin_name', $admin->name);

        //Redirect ke halaman showAccount (/admin/Account)
        return redirect('/admin/Account')->with('success', 'Profil berhasil diperbarui!');
    }

    public function deleteAccount() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum 
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //query
        $adminID = Session::get('admin_id'); //ambil adminID dari session
    
        if ($adminID) { //jika adminID ditemukan dari session
            //Memulai Transaction ke database
            DB::beginTransaction();
            try {
                //ubah foreign key adminID disetiap event menjadi null (query)
                Event::where('adminID', $adminID)->update(['adminID' => null]);
                
                //ubah foreign key adminID disetiap destination menjadi null (query)
                Destination::where('adminID', $adminID)->update(['adminID' => null]);

                //delete adminnya berdasarkan adminID (query)
                Admin::where('adminID', $adminID)->delete();

                //commit transaction
                DB::commit(); 

                //bersihkan session
                Session::flush();

                //redirect ke homepage (/)
                return redirect('/')->with('success', 'Akun berhasil dihapus!');

            } catch (\Exception $e) {
                //Jika ada yang salah, rollback transaction
                DB::rollBack();
                //jika salah, Kembali ke halaman showAccount dengan pesan error
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        }
        //tampillkan pesan error jika $adminID tidak ditemukan di session
        return back()->with('error', 'Gagal menghapus akun.');
    }
}