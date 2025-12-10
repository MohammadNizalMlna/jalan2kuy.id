<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function tampilCategoryAdmin() {
        return view('admin.destination.destinationAdmin'); // Mengarah ke file ke-2 di atas
    }

    public function tampilCategory(){
        return view('destination.destination');
    }

    public function categoryAdmin(Request $request) {
        // Ambil parameter ?category=nature dari URL
        $categoryName = $request->query('Category'); 
        
        // Return view kategori (File ke-1 di atas)
        return view('admin.destination.categoryAdmin', ['category' => $categoryName]);
    }

    public function category(Request $request) {
        // Ambil parameter ?category=nature dari URL
        $categoryName = $request->query('Category'); 
        
        // Return view kategori (File ke-1 di atas)
        return view('destination.category', ['category' => $categoryName]);
    }
}
