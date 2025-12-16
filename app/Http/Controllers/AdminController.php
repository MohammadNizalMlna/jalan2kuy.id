<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                
                // [UBAHAN DISINI]: Jangan langsung set admin_id (login penuh).
                // Simpan ID sementara untuk verifikasi passkey
                Session::put('temp_admin_id', $usernameAdmin->adminID);
                Session::put('temp_admin_name', $usernameAdmin->name);
                
                // Redirect ke halaman Passkey
                return redirect('/verifikasi-login');
            }
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function showVerifikasiLogin() {
        // Cek apakah user sudah melewati tahap login awal?
        if (!Session::has('temp_admin_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('akun.passkey');
    }

    public function prosesVerifikasiLogin(Request $request) {
        $inputPassKey = $request->input('passkey_code');
        $passKey = '123456'; //PassKey dibuat statis

        if ($inputPassKey === $passKey) {
            //Ambil ID dari session sementara
            $adminID = Session::get('temp_admin_id');
            $adminName = Session::get('temp_admin_name');

            //resmikan Login (Set session asli)
            Session::put('admin_id', $adminID);
            Session::put('admin_name', $adminName);

            //Hapus session sementara
            Session::forget('temp_admin_id');
            Session::forget('temp_admin_name');

            //Masuk ke Homepage Admin
            return redirect('admin/Homepage');
        } else {
            //jika salah, Kembali ke halaman passkey dengan pesan error
            return back()->with('error', 'Passkey salah, silakan coba lagi.');
        }
    }

    public function logout() {
        // Hapus semua data session
        Session::flush();
        
        // Redirect ke halaman utama/login
        return redirect('/')->with('success', 'Berhasil Logout.');
    }

    public function register(Request $request) {
        //VALIDASI INPUT
        $request->validate([
            'name'     => 'required',
            'username' => 'required|alpha_num|unique:admin,username', 
            'email'    => 'required|email|unique:admin,email',
            'gender'   => 'required',
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

        //MEMULAI TRANSACTION
        DB::beginTransaction();

        try {
            //geberate adminID baru
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

            Log::error("Gagal Register: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
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
            //hapus objek admin dimana ID nya sesuai dengan $adminID dari database
            Admin::where('adminID', $adminID)->delete();
            //Hapus session
            Session::flush();
            //redirect ke homepage
            return redirect('/')->with('success', 'Akun berhasil dihapus.');
        }
        //jika gagal, kembali ke halaman sebelumnya dengan pesan gagal menghapus akun
        return back()->with('error', 'Gagal menghapus akun.');
    } 
}