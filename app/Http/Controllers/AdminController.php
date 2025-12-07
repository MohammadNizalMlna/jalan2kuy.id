<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Wajib import Session

class AdminController extends Controller
{
    // --- 1. FUNGSI LOGIN (Dimodifikasi) ---
    public function login(Request $request)
    {
        $usernameInput = $request->input('username');
        $passwordInput = $request->input('password');

        $admin = Admin::where('username', $usernameInput)->first();

        if ($admin) {
            // Cek Password (Plain text sesuai request anda)
            if ($admin->password === $passwordInput) {
                
                // [UBAHAN DISINI]: Jangan langsung set admin_id (login penuh).
                // Simpan ID sementara untuk verifikasi passkey
                Session::put('temp_admin_id', $admin->adminID);
                Session::put('temp_admin_name', $admin->name);
                
                // Redirect ke halaman Passkey
                return redirect('/verifikasi-login');
            }
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    // --- 2. TAMPILKAN HALAMAN PASSKEY(Baru) ---
    public function showVerifikasiLogin()
    {
        // Cek apakah user sudah melewati tahap login awal?
        if (!Session::has('temp_admin_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('akun.passkey');
    }

    // --- 3. PROSES CEK KODE PASSKEY (Baru) ---
    public function prosesVerifikasiLogin(Request $request)
    {
        // Ambil kode gabungan dari input hidden
        $inputCode = $request->input('passkey_code');
        $correctCode = '123456'; // Kode statis sesuai request

        if ($inputCode === $correctCode) {
            // JIKA KODE BENAR:
            // 1. Ambil ID dari session sementara
            $adminID = Session::get('temp_admin_id');
            $adminName = Session::get('temp_admin_name');

            // 2. Resmikan Login (Set session asli)
            Session::put('admin_id', $adminID);
            Session::put('admin_name', $adminName);

            // 3. Hapus session sementara
            Session::forget('temp_admin_id');
            Session::forget('temp_admin_name');

            // 4. Masuk ke Homepage Admin
            return redirect('admin/Homepage');
        } else {
            // JIKA SALAH: Kembali ke halaman passkey dengan error
            return back()->with('error', 'Passkey salah, silakan coba lagi.');
        }
    }

    public function register(Request $request)
    {
        // 1. VALIDASI INPUT (Opsional tapi sangat disarankan)
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:admin,username', // Pastikan username unik
            'password' => 'required',
            'email' => 'required|email|unique:admin,email',
            'gender' => 'required',
        ]);

        // 2. MEMULAI TRANSACTION
        // Sama seperti connection.setAutoCommit(false) di Java
        DB::beginTransaction();

        try {
            // --- LOGIKA GENERATE ID (adm001, adm002, dst) ---
        
            // A. Ambil admin terakhir berdasarkan adminID (lockForUpdate untuk mencegah tabrakan data saat 2 orang daftar bersamaan)
            $lastAdmin = Admin::orderBy('adminID', 'desc')->lockForUpdate()->first();

            $newAdminID = 'adm001'; // Default jika belum ada admin sama sekali

            if ($lastAdmin) {
                // Ambil ID terakhir, misal "adm015"
                $lastID = $lastAdmin->adminID;
            
                // Ambil angkanya saja: substring dari indeks ke-3 ("015") -> diubah ke int (15)
                // PHP: substr($string, start)
                $number = (int) substr($lastID, 3);
            
                // Tambah 1
                $number++;
            
                // Format ulang jadi "adm" + 3 digit angka ("adm016")
                // PHP: sprintf('%03d', angka) sama dengan String.format("%03d", num) di Java
                $newAdminID = 'adm' . sprintf("%03d", $number);
            }

        // --- SIMPAN DATA KE DATABASE ---
        
            $admin = new Admin();
            $admin->adminID = $newAdminID;
            $admin->name = $request->input('name');
            $admin->username = $request->input('username');
        
            // Catatan: Sebaiknya password di-Hash menggunakan: Hash::make($request->input('password'))
            // Tapi kita ikuti logika Java Anda (Plain Text) dulu:
            $admin->password = $request->input('password'); 
        
            $admin->email = $request->input('email');
        
            // Konversi input gender (misal dari radio button "1" atau "0") ke boolean
            $admin->gender = filter_var($request->input('gender'), FILTER_VALIDATE_BOOLEAN);
        
            $admin->save(); // Eksekusi INSERT

            // 3. COMMIT TRANSACTION
            // Sama seperti connection.commit()
            DB::commit();

            // Redirect jika sukses
            return redirect('/login')->with('success', 'Registrasi Berhasil! Silakan Login.');

        } catch (\Exception $e) {
            // 4. ROLLBACK TRANSACTION
            // Sama seperti connection.rollback()
            DB::rollBack();

            // Log error untuk developer (cek di storage/logs/laravel.log)
            Log::error("Gagal Register: " . $e->getMessage());

            // Kembali ke form register dengan pesan error
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
    }

    // A. Menampilkan Halaman Akun
    public function showAccount()
    {
        // Ambil ID dari session login
        $adminID = Session::get('admin_id');
    
        if (!$adminID) {
            return redirect('/login')->with('error', 'Sesi habis, silakan login kembali.');
        }

        // Cari data admin lengkap di database
        $admin = Admin::find($adminID);

        // Kirim data $admin ke view
        return view('akun.accountAdmin', ['admin' => $admin]);
    }

    // B. Proses Logout
    public function logout()
    {
        // Hapus semua data session
        Session::flush();
        
        // Redirect ke halaman utama/login
        return redirect('/')->with('success', 'Berhasil Logout.');
    }

    // C. Proses Hapus Akun
    public function deleteAccount()
    {
        $adminID = Session::get('admin_id');
    
        if ($adminID) {
            // Hapus dari database
            Admin::where('adminID', $adminID)->delete();
        
            // Hapus session (Logout paksa)
            Session::flush();
        
            return redirect('/')->with('success', 'Akun berhasil dihapus.');
        }
    
        return back()->with('error', 'Gagal menghapus akun.');
    }

    // A. TAMPILKAN FORM EDIT
public function editProfile()
{
    $adminID = Session::get('admin_id');
    $admin = Admin::find($adminID);

    if (!$admin) {
        return redirect('/login');
    }

    return view('akun.editAkun', ['admin' => $admin]);
}

// B. PROSES UPDATE DATA
public function updateProfile(Request $request)
{
    $adminID = Session::get('admin_id');
    $admin = Admin::find($adminID);

    // 1. Validasi Input
    $request->validate([
        'name' => 'required',
        // Rule Unique: Cek unik di tabel admin kolom username, TAPI abaikan ID milik admin yang sedang login ini
        'username' => 'required|unique:admin,username,' . $adminID . ',adminID',
        'email' => 'required|email|unique:admin,email,' . $adminID . ',adminID',
        'gender' => 'required',
        'password' => 'nullable|min:4' // Password boleh kosong (nullable)
    ]);

    // 2. Update Data
    $admin->name = $request->input('name');
    $admin->username = $request->input('username');
    $admin->email = $request->input('email');
    $admin->gender = filter_var($request->input('gender'), FILTER_VALIDATE_BOOLEAN);

    // 3. Cek apakah user mengisi password baru?
    if ($request->filled('password')) {
        $admin->password = $request->input('password'); // Simpan password baru
    }

    $admin->save();

    // 4. Update Session Nama (jika nama berubah)
    Session::put('admin_name', $admin->name);

    return redirect('/admin/Account')->with('success', 'Profil berhasil diperbarui!');
}
}