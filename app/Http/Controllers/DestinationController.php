<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Destination; 
use App\Models\DestCategory;
use App\Models\Event; // Tambahkan ini agar tidak error saat addDestination

class DestinationController extends Controller
{
    // Halaman Utama Destinasi Admin (Mungkin list semua kategori)
    public function tampilCategoryAdmin() {
        // 1. Ambil semua data kategori dari database
        $categories = DestCategory::all();

        return view('admin.destination.destinationAdmin', compact('categories')); 
    }

    // Halaman Utama Destinasi User
    public function tampilCategory(){
        // 1. Ambil semua data kategori dari database
        $categories = DestCategory::all();

        return view('destination.destination', compact('categories'));
    }

    // --- PENGGANTI CATEGORY.JS (VERSI ADMIN) ---
    public function categoryAdmin(Request $request) {
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
        $categoryName = $request->query('Category'); 
        
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

    // Form Tambah Destinasi
    public function addDestination(Request $request)
    {
        // Ambil semua event untuk dropdown
        $events = Event::all();
        
        // Tampilkan view
        return view('admin.destination.addDestination', compact('events'));
    }

    // Proses Simpan Data
    public function storeDestinationData(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'description'=> 'required',
            'entranceFee' =>'required|numeric',
            'openingDay' => 'required',
            'closingDay'=> 'required',
            'openingHours'=> 'required',
            'closingHours'=> 'required',
            'timezone' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'thumbnailImage' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'category' => 'required', // Ini sekarang isinya ID (angka)
        ]);

        DB::beginTransaction();
        try {
            // 2. GENERATE ID (dst001, dst002...)
            $lastDestination = Destination::orderBy('destinationID', 'desc')->lockForUpdate()->first();
            $newDestinationID = 'dst001';

            if ($lastDestination) {
                $number = (int) substr($lastDestination->destinationID, 3);
                $number++;
                $newDestinationID = 'dst' . sprintf("%03d", $number);
            }

            // 3. UPLOAD GAMBAR
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('destinations/image', 'public');
            }
            
            $thumbnailImagePath = null;
            if ($request->hasFile('thumbnailImage')) {
                $thumbnailImagePath = $request->file('thumbnailImage')->store('destinations/thumbnailImage', 'public');
            }

            // 4. SIMPAN KE DB
            $destination = new Destination();
            
            $destination->destinationID = $newDestinationID; 
            $destination->name = $request->name;
            $destination->location = $request->location;
            $destination->description = $request->description;
            $destination->entranceFee = $request->entranceFee;
            $destination->openingDay = $request->openingDay; 
            $destination->closingDay = $request->closingDay; 
            $destination->openingHours = $request->openingHours;
            $destination->closingHours = $request->closingHours;
            $destination->timezone = $request->timezone;
            $destination->imagePath = $imagePath;
            $destination->thumbnailImagePath = $thumbnailImagePath;
            $destination->adminID = Session::get('admin_id');
            
            // --- PERUBAHAN PENTING DI SINI ---
            // Kita mencari berdasarkan ID (Primary Key), bukan Nama lagi.
            $kategori = DestCategory::find($request->category);
            
            if ($kategori) {
                $destination->destCategoryID = $kategori->destCategoryID;
            } else {
                // Jika ID dimanipulasi user dan tidak ketemu
                return back()->with('error', 'Kategori tidak valid!');
            }

            $destination->save();

            /// --- UPDATE LOGIKA EVENT (ARRAY) ---
            // Cek apakah ada input eventID dan pastikan dia Array
            if ($request->filled('eventID') && is_array($request->eventID)) {
                
                // Loop setiap ID event yang dipilih
                foreach ($request->eventID as $singleEventID) {
                    
                    // Pastikan ID tidak kosong (karena dropdown terakhir biasanya kosong)
                    if (!empty($singleEventID)) {
                        $relatedEvent = \App\Models\Event::find($singleEventID);
                        
                        // Jika event ketemu, update destinationID-nya
                        if ($relatedEvent) {
                            $relatedEvent->destinationID = $destination->destinationID;
                            $relatedEvent->save();
                        }
                    }
                }
            }

            DB::commit();

            // Redirect: Kita ambil nama kategori dari hasil pencarian DB agar URL tetap cantik
            // Contoh: ...?Category=Nature (Bukan ?Category=1)
            return redirect('/admin/Destination/Category?Category=' . $kategori->destCategoryID)
                ->with('success', 'Destinasi berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembalikan ke form dengan pesan error (bukan dd)
            return back()->with('error', 'Gagal menyimpan destination: ' . $e->getMessage())->withInput();
        }
    }

    public function detailDestinationAdmin($id)
    {
        // 1. Ambil Data Destinasi (Tanpa 'with')
        $destination = Destination::where('destinationID', $id)->first();

        if (!$destination) {
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }

        // 2. Ambil Data Event SECARA MANUAL
        // "Cari di tabel Event, yang destinationID-nya sama dengan ID destinasi ini"
        $relatedEvents = Event::where('destinationID', $id)->get();

        // 3. Kirim KEDUA variabel ($destination dan $relatedEvents) ke View
        return view('admin.destination.detailDestinationAdmin', [
            'destination' => $destination,
            'events'      => $relatedEvents // Kita namakan 'events' biar mudah dipanggil di Blade
        ]);
    }

    // Function Delete
    public function deleteDestination($id)
    {
        $destination = Destination::where('destinationID', $id)->first();
        
        if ($destination) {
            // 1. LEPASKAN RELASI EVENT DULU (PENTING!)
            // Cari semua event yang punya destinationID ini, lalu set jadi NULL
            \App\Models\Event::where('destinationID', $id)->update(['destinationID' => null]);

            // 2. Hapus file gambar (Kode lama kamu)
            if ($destination->imagePath) Storage::disk('public')->delete($destination->imagePath);
            if ($destination->thumbnailImagePath) Storage::disk('public')->delete($destination->thumbnailImagePath);

            // 3. Baru Hapus Destinasinya
            $destination->delete();

            return redirect('/admin/Destination/Category?Category=' . $destination->destCategoryID)->with('success', 'Destinasi berhasil dihapus (Event terkait telah di-unassign).');
        }

        return back()->with('error', 'Gagal menghapus data.');
    }

    public function tampilFormEditDestination($id)
    {
        $destination = Destination::where('destinationID', $id)->first();
        
        if (!$destination) {
            return back()->with('error', 'Destinasi tidak ditemukan.');
        }

        // 1. Ambil SEMUA event (untuk opsi dropdown menambah event baru/ganti event)
        $allEvents = Event::all(); 

        // 2. Ambil EVENT MILIK DESTINASI INI (Manual Query pengganti $destination->events)
        // "Cari di tabel Event, yang destinationID-nya sama dengan ID destinasi ini"
        $currentEvents = Event::where('destinationID', $id)->get();
        
        // 3. Kirim ke View (perhatikan variabel 'currentEvents')
        return view('admin.destination.editDestinationAdmin', [
            'destination'   => $destination,
            'events'        => $allEvents,     // List semua event
            'currentEvents' => $currentEvents  // List event milik destinasi ini saja
        ]);
    }

    // PROSES UPDATE
    public function editDestination(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'description' => 'required',
            'entranceFee' => 'required|numeric',
            'openingDay' => 'required',
            'closingDay' => 'required',
            'openingHours' => 'required',
            'closingHours' => 'required',
            'timezone' => 'required',
            'image' => 'nullable|image|max:10240',          // Nullable (karena edit)
            'thumbnailImage' => 'nullable|image|max:10240', // Nullable (karena edit)
        ]);

        try {
            $destination = Destination::where('destinationID', $id)->first();
            
            // Update Gambar (Hanya jika ada upload baru)
            if ($request->hasFile('image')) {
                if ($destination->imagePath) Storage::disk('public')->delete($destination->imagePath);
                $destination->imagePath = $request->file('image')->store('destinations/image', 'public');
            }
            if ($request->hasFile('thumbnailImage')) {
                if ($destination->thumbnailImagePath) Storage::disk('public')->delete($destination->thumbnailImagePath);
                $destination->thumbnailImagePath = $request->file('thumbnailImage')->store('destinations/thumbnailImage', 'public');
            }

            // Update Data Teks
            $destination->name = $request->name;
            $destination->description = $request->description;
            $destination->location = $request->location;
            $destination->openingDay = $request->openingDay;
            $destination->closingDay = $request->closingDay;
            $destination->openingHours = $request->openingHours;
            $destination->closingHours = $request->closingHours;
            $destination->timezone = $request->timezone;
            $destination->entranceFee = $request->entranceFee;
            
            $destination->save();

            // --- UPDATE LOGIKA EVENT (RESET & RE-ASSIGN) ---
            // 1. Lepaskan (Detach) semua event yang sebelumnya nempel ke destinasi ini
            //    (Set destinationID mereka jadi NULL dulu)
            \App\Models\Event::where('destinationID', $id)->update(['destinationID' => null]);

            // 2. Pasang kembali event-event yang dipilih di form
            if ($request->filled('eventID') && is_array($request->eventID)) {
                foreach ($request->eventID as $evID) {
                    if (!empty($evID)) {
                        $event = \App\Models\Event::find($evID);
                        if ($event) {
                            $event->destinationID = $id; // Pasang lagi
                            $event->save();
                        }
                    }
                }
            }

            return redirect('/admin/Destination/Detail/' . $id)->with('success', 'Destinasi berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}