<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Destination; 
use App\Models\DestCategory;

class DestCategoryController extends Controller { //penamaan controller menggunakan huruf kapital pada awal masing-masing kata
    //Function untuk menampilkan halaman Utama Destinasi User
    public function tampilCategory(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //logika jika user melakukan searching
        if ($request->has('search')) { //penamaan variabel diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
            $keyword = $request->search;
            
            //cari destination berdasarkan nama yang mengandung unsur seperti yang diinputkan oleh user (keyword) (query)
            $destination = Destination::where('name', 'LIKE', '%'.$keyword.'%')->get();

            //redirect ke tampilan destinationSearch dengan membawa data $destination dan $keyword
            return view('destination.destinationSearch', ['destination' => $destination, 'keyword' => $keyword]);
        }

        //jika user tidak melakukan searching, ambil data kategori dan masukkan kedalam $categories
        $categories = DestCategory::all();

        //redirect ke halaman destination dengan membawa data $categories
        return view('destination.destination', ['categories' => $categories]);
    }

    //function untuk menampilkan destinasi-destinasi yang ada dalam sebuah kategori
    public function category(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //ambil parameter ?Category={$destCategoryID} dari URL
        $destCategoryID = $request->query('Category'); 
        
        //ambil data kategori dimana ID nya sama dengan $destCategoryID, simpan kedalam $category
        $category = DestCategory::where('destCategoryID', $destCategoryID)->first();

        //ambil data nama kategori dimana ID nya sama dengan $destCategoryID, simpan kedalam $categoryName
        $categoryName = DestCategory::where('destCategoryID', $destCategoryID)->value('categoryName');

        if ($category) { //jika $category ditemukan
            $destination = Destination::where('destCategoryID', $category->destCategoryID)->get(); //ambil data destination dimana destCategoryID nya sesuai dengan $category->destCategoryID
        } else {
            $destination = collect();
        }
        //redirect ke halaman destination.category dengan membawa data $categoryName, $destCategoryID, $destination
        return view('destination.category', ['categoryName' => $categoryName, 'destCategoryID' => $destCategoryID, 'destination' => $destination]);
    }

    //===FUNCTION-FUNCTION DIBAWAH INI KHUSUS ADMIN ONLY===

    //Function untuk menampilkan halaman Utama Destinasi Admin
    public function tampilCategoryAdmin(Request $request){ //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        
        //logika jika user melakukan searching
        if ($request->has('search')) {
            $keyword = $request->search;
            
            //cari destination berdasarkan nama yang mengandung unsur seperti yang diinputkan oleh user (keyword) (query)
            $destination = Destination::where('name', 'LIKE', '%'.$keyword.'%')->get();
            
            //redirect ke tampilan destinationSearchAdmin dengan membawa data $destination dan $keyword
            return view('admin.destination.destinationSearchAdmin', ['destination' => $destination, 'keyword' => $keyword]);
        }

        //jika user tidak melakukan searching, ambil data kategori dan masukkan kedalam $categories
        $categories = DestCategory::all();

        //redirect ke halaman destinationAdmin dengan membawa data $categories
        return view('admin.destination.destinationAdmin', compact('categories'));
    }

    //function untuk menampilkan destinasi-destinasi yang ada dalam sebuah kategori
    public function categoryAdmin(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //ambil parameter ?Category={$destCategoryID} dari URL
        $destCategoryID = $request->query('Category'); 
        
        //ambil data kategori dimana ID nya sama dengan $destCategoryID, simpan kedalam $category
        $category = DestCategory::where('destCategoryID', $destCategoryID)->first();

        //ambil data nama kategori dimana ID nya sama dengan $destCategoryID, simpan kedalam $categoryName
        $categoryName = DestCategory::where('destCategoryID', $destCategoryID)->value('categoryName');

        if ($category) { //jika $category ditemukan
            $destination = Destination::where('destCategoryID', $category->destCategoryID)->get(); //ambil data destination dimana destCategoryID nya sesuai dengan $category->destCategoryID
        } else {
            // Jika kategori tidak valid/kosong, kembalikan array kosong agar tidak error
            $destination = collect(); 
        }

        //redirect ke halaman destination.category dengan membawa data $categoryName, $destCategoryID, $destination
        return view('admin.destination.categoryAdmin', ['categoryName' => $categoryName, 'destCategoryID' => $destCategoryID, 'destination' => $destination]);
    }
}
