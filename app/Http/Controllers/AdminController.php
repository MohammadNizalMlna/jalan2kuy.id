<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use \App\Models\Event;
use \App\Models\Destination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller {
    
    public function login(Request $request) {
        $usernameInput = $request->input('username');
        $passwordInput = $request->input('password');

        $usernameAdmin = Admin::where('username', $usernameInput)->first();

        if ($usernameAdmin) {
            // Cek Password apakah sama dengan yang disimpan didalam database atau tidak
            if (Hash::Check($passwordInput, $usernameAdmin->password)) {

                //simpan sementara adminID didalam temp_admin_id untuk dipakai pada verifikasi passkey
                //simpan sementara name didalam temp_admin_name untuk dipakai pada verifikasi passkey
                Session::put('temp_admin_id', $usernameAdmin->adminID);
                Session::put('temp_admin_name', $usernameAdmin->name);
                
                //Redirect ke URL halaman Passkey (/verifikasi-login)
                return redirect('/verifikasi-login');
            }
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function showVerifikasiLogin() {
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('temp_admin_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        //redirect ke halaman passkey
        return view('akun.passkey');
    }

    public function prosesVerifikasiLogin(Request $request) {
        //simpan passkey yang diinputkan user kedalam $inputPassKey
        $inputPassKey = $request->input('passkey_code');
        $passKey = '123456'; //PassKey dibuat statis

        if ($inputPassKey === $passKey) {
            //Ambil temp_admin_id dan temp_admin_name dari session sementara
            $adminID = Session::get('temp_admin_id');
            $adminName = Session::get('temp_admin_name');

            //resmikan Login (Set session asli)
            Session::put('admin_id', $adminID);
            Session::put('admin_name', $adminName);

            //hapus session sementara
            Session::forget('temp_admin_id');
            Session::forget('temp_admin_name');

            //masuk ke Homepage Admin
            return redirect('admin/Homepage');
        } else {
            //jika salah, Kembali ke halaman passkey dengan pesan error
            return back()->with('error', 'Passkey salah, silakan coba lagi.');
        }
    }

    public function register(Request $request) {
        //Validasi input
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

        //Memulai Transaction
        DB::beginTransaction();

        try {
            //generate adminID baru
            $lastAdmin = Admin::orderBy('adminID', 'desc')->lockForUpdate()->first();
            $newAdminID = 'adm001'; 

            if ($lastAdmin) {
                $lastID = $lastAdmin->adminID;
                $number = (int) substr($lastID, 3);
                $number++;
                $newAdminID = 'adm' . sprintf("%03d", $number);
            }

            //buat objek Admin dan siimpan datanya ke database
            $admin = new Admin();
            $admin->adminID = $newAdminID;
            $admin->name = $request->input('name');
            $admin->username = $request->input('username');      
            $admin->password = Hash::make($request->input('password')); //password disimpan dalam bentuk hash
            $admin->email = $request->input('email');
            $admin->gender = filter_var($request->input('gender'), FILTER_VALIDATE_BOOLEAN);
            $admin->save(); 

            //commit transaction
            DB::commit();

            return redirect('/login')->with('success', 'Registrasi Berhasil! Silakan Login.');

        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();

            return back()->with('error', 'Gagal register : ' . $e->getMessage())->withInput();
        }
    }

    //===FUNCTION-FUNCTION DIBAWAH INI KHUSUS ADMIN ONLY===

    public function logout() {
        //Hapus semua data session
        Session::flush();
        
        //Redirect ke halaman utama/login
        return redirect('/')->with('success', 'Berhasil Logout.');
    }

    public function showAccount() {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //ambil adminID dari session 
        $adminID = Session::get('admin_id');

        //Cari data admin lengkap di database
        $admin = Admin::find($adminID);

        //Kirim data $admin ke view accountAdmin
        return view('akun.accountAdmin', ['admin' => $admin]);
    }

    public function tampilFormEditProfile() {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //ambil adminID dari session
        $adminID = Session::get('admin_id');

        //cari data $admin yang memiliki ID $adminID
        $admin = Admin::find($adminID);

        //kalau tidak ada $admin, kembalikan ke halaman login
        if (!$admin) {
            return redirect('/login');
        }
        //kirim data $admin ke view editAkun
        return view('akun.editAkun', ['admin' => $admin]);
    }

    public function editProfile(Request $request) {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //ambil adminID dari session
        $adminID = Session::get('admin_id');

        //cari data $admin yang memiliki ID $adminID
        $admin = Admin::find($adminID);

        //Validasi Input
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:admin,username,' . $adminID . ',adminID',
            'email' => 'required|email|unique:admin,email,' . $adminID . ',adminID',
            'gender' => 'required',
            'password' => 'nullable|min:8'
        ]);

        //Update Data dari input yang sudah divalidasi
        $admin->name = $request->input('name');
        $admin->username = $request->input('username');
        $admin->email = $request->input('email');
        $admin->gender = filter_var($request->input('gender'), FILTER_VALIDATE_BOOLEAN);
        //Cek apakah user mengisi password baru?
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }
        $admin->save();

        //Update Session Nama (jika nama berubah)
        Session::put('admin_name', $admin->name);
        return redirect('/admin/Account')->with('success', 'Profil berhasil diperbarui!');
    }

    public function deleteAccount() {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //ambil adminID dari session
        $adminID = Session::get('admin_id');
    
        if ($adminID) {
            //Memulai Transaction
            DB::beginTransaction();
            try {
                //ubah foreign key adminID disetiap event menjadi null
                Event::where('adminID', $adminID)->update(['adminID' => null]);
                
                //ubah foreign key adminID disetiap destination menjadi null
                Destination::where('adminID', $adminID)->update(['adminID' => null]);

                //delete adminnya berdasarkan adminID
                Admin::where('adminID', $adminID)->delete();

                //commit transaction
                DB::commit(); 

                //bersikah session
                Session::flush();

                //redirect ke homepage 
                return redirect('/')->with('success', 'Akun berhasil dihapus!');

            } catch (\Exception $e) {
                //Jika ada yang salah, rollback transaction
                DB::rollBack();
                //tampilkan pesan error jika gagal
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        }
        //tampillkan pesan error jika $adminID tidak ditemukan di session
        return back()->with('error', 'Gagal menghapus akun.');
    }
}