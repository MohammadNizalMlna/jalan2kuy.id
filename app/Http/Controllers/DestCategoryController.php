<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Destination; 
use App\Models\DestCategory;

class DestCategoryController extends Controller
{
    // Halaman Utama Destinasi Admin (Mungkin list semua kategori)
    // Lakukan hal yang sama untuk 'tampilCategoryAdmin'
    public function tampilCategoryAdmin(Request $request){
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        
        // 1. Cek apakah user melakukan pencarian?
        if ($request->has('search')) {
            $keyword = $request->search;
            
            // Cari destinasi berdasarkan nama (gunakan LIKE)
            $destinations = Destination::where('name', 'LIKE', '%'.$keyword.'%')->get();

            // LEMPAR KE VIEW BARU (destinationSearch.blade.php)
            // Kita kirim data $destinations dan $keyword
            return view('admin.destination.destinationSearchAdmin', compact('destinations', 'keyword'));
        }

        // 2. Jika TIDAK mencari (Tampilan Awal / Default)
        $categories = DestCategory::all();
        return view('admin.destination.destinationAdmin', compact('categories'));
    }

    // Halaman Utama Destinasi User
    public function tampilCategory(Request $request){
        
        // 1. Cek apakah user melakukan pencarian?
        if ($request->has('search')) {
            $keyword = $request->search;
            
            // Cari destinasi berdasarkan nama (gunakan LIKE)
            $destinations = Destination::where('name', 'LIKE', '%'.$keyword.'%')->get();

            // LEMPAR KE VIEW BARU (destinationSearch.blade.php)
            // Kita kirim data $destinations dan $keyword
            return view('destination.destinationSearch', compact('destinations', 'keyword'));
        }

        // 2. Jika TIDAK mencari (Tampilan Awal / Default)
        $categories = DestCategory::all();
        return view('destination.destination', compact('categories'));
    }

    // --- PENGGANTI CATEGORY.JS (VERSI ADMIN) ---
    public function categoryAdmin(Request $request) {
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        // 1. Ambil parameter ?Category=Nature dari URL
        $destCategoryID = $request->query('Category'); 
        
        // 2. Cari ID Kategori berdasarkan namanya di database
        $category = DestCategory::where('destCategoryID', $destCategoryID)->first();
        $destCategoryName = DestCategory::where('destCategoryID', $destCategoryID)->value('categoryName');

        // 3. Logika Pengambilan Data
        if ($category) {
            // Jika kategori ditemukan, ambil semua destinasi yang punya ID kategori tersebut
            $destinations = Destination::where('destCategoryID', $category->destCategoryID)->get();
        } else {
            // Jika kategori tidak valid/kosong, kembalikan array kosong agar tidak error
            $destinations = collect(); 
        }

        // 4. Return view dengan membawa data destinasi
        return view('admin.destination.categoryAdmin', [
            'categoryName' => $destCategoryName,
            'destCategoryID' => $destCategoryID,
            'destinations' => $destinations // <-- Data ini yang akan diloop di Blade
        ]);
    }

    // --- PENGGANTI CATEGORY.JS (VERSI USER) ---
    public function category(Request $request) {
        $destCategoryID = $request->query('Category'); 
        // $categoryName = $request->query('Category'); 
        
        $category = DestCategory::where('destCategoryID', $destCategoryID)->first();
        $destCategoryName = DestCategory::where('destCategoryID', $destCategoryID)->value('categoryName');

        if ($category) {
            $destinations = Destination::where('destCategoryID', $category->destCategoryID)->get();
        } else {
            $destinations = collect();
        }
        
        return view('destination.category', [
            'categoryName' => $destCategoryName,
            'destCategoryID' => $destCategoryID,
            'destinations' => $destinations
        ]);
    }
}
