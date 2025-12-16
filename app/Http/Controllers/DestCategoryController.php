<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Destination; 
use App\Models\DestCategory;

class DestCategoryController extends Controller {
    //Function untuk menampilkan halaman Utama Destinasi User
    public function tampilCategory(Request $request) {
        //logika jika user melakukan searching
        if ($request->has('search')) {
            $keyword = $request->search;
            
            //cari destination berdasarkan nama yang mengandung unsur seperti yang diinputkan oleh user (keyword)
            $destinations = Destination::where('name', 'LIKE', '%'.$keyword.'%')->get();

            //redirect ke tampilan destinationSearch dengan membawa data $destinations dan $keyword
            return view('destination.destinationSearch', compact('destinations', 'keyword'));
        }

        //jika user tidak melakukan searching, ambil data kategori dan masukkan kedalam $categories
        $categories = DestCategory::all();
        //redirect ke halaman destination dengan membawa data $categories
        return view('destination.destination', compact('categories'));
    }

    //function untuk menampilkan destinasi-destinasi yang ada dalam sebuah kategori
    public function category(Request $request) {
        //ambil parameter ?Category={$destCategoryID} dari URL
        $destCategoryID = $request->query('Category'); 
        
        //ambil data kategori dimana ID nya sama dengan $destCategoryID, simpan kedalam $category
        $category = DestCategory::where('destCategoryID', $destCategoryID)->first();

        //ambil data nama kategori dimana ID nya sama dengan $destCategoryID, simpan kedalam $categoryName
        $categoryName = DestCategory::where('destCategoryID', $destCategoryID)->value('categoryName');

        if ($category) {
            $destinations = Destination::where('destCategoryID', $category->destCategoryID)->get();
        } else {
            $destinations = collect();
        }
        
        return view('destination.category', [
            'categoryName' => $categoryName,
            'destCategoryID' => $destCategoryID,
            'destinations' => $destinations
        ]);
    }

    //===FUNCTION-FUNCTION DIBAWAH INI KHUSUS ADMIN ONLY===

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

    // --- PENGGANTI CATEGORY.JS (VERSI ADMIN) ---
    public function categoryAdmin(Request $request) {
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        // 1. Ambil parameter ?Category=Nature dari URL
        $destCategoryID = $request->query('Category'); 
        
        // 2. Cari ID Kategori berdasarkan namanya di database
        $category = DestCategory::where('destCategoryID', $destCategoryID)->first();
        $categoryName = DestCategory::where('destCategoryID', $destCategoryID)->value('categoryName');

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
            'categoryName' => $categoryName,
            'destCategoryID' => $destCategoryID,
            'destinations' => $destinations // <-- Data ini yang akan diloop di Blade
        ]);
    }
}
