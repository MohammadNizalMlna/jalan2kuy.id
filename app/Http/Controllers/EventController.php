<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Event; 

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Mulai Query
        $query = Event::query();

        // Logika Filter Tanggal (Jika user mengisi input tanggal)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('startDate', [
                $request->input('start_date'), 
                $request->input('end_date')
            ]);
        }

        // Ambil data (urutkan dari yang terbaru)
        $events = $query->orderBy('startDate', 'desc')->get();

        // Tampilkan View dengan membawa data events
        return view('admin.eventAdmin.eventAdmin', ['events' => $events]);
    }

    public function tampilkanDetailEvent($id)
    { 
        // Cari event berdasarkan eventID
        $event = Event::where('eventID', $id)->first();

        // Jika tidak ketemu, kembalikan ke list
        if (!$event) {
            return redirect('/admin/Event')->with('error', 'Event tidak ditemukan.');
        }

        // Tampilkan view detailEvent dengan membawa data $event
        return view('admin.eventAdmin.detailEventAdmin', ['event' => $event]);
    }

    // A. TAMPILKAN FORM ADD
    public function addEvent()
    {
        return view('admin.eventAdmin.addEvent');
    }

    // B. PROSES SIMPAN DATA (STORE)
    public function storeEventData(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'description' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'startTime' => 'required',
            'endTime' => 'required',
            'entranceFee' => 'required|numeric',
            'socialMedia' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        DB::beginTransaction();
        try {
            // 2. GENERATE ID (evt001, evt002...)
            $lastEvent = Event::orderBy('eventID', 'desc')->lockForUpdate()->first();
            $newEventID = 'evt001';

            if ($lastEvent) {
                $number = (int) substr($lastEvent->eventID, 3);
                $number++;
                $newEventID = 'evt' . sprintf("%03d", $number);
            }

            // 3. UPLOAD GAMBAR
            // Simpan di folder: storage/app/public/events
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('events', 'public');
            }

            // 4. SIMPAN KE DATABASE
            $event = new Event();
            $event->eventID = $newEventID;
            $event->name = $request->name;
            $event->location = $request->location;
            $event->description = $request->description;
            $event->entranceFee = $request->entranceFee;
            $event->socialMedia = $request->socialMedia;
            
            $event->startDate = $request->startDate;
            $event->endDate = $request->endDate;
            $event->startTime = $request->startTime;
            $event->endTime = $request->endTime;
            
            $event->imagePath = $imagePath; // Simpan path gambar
            $event->adminID = Session::get('admin_id');
            $event->destinationID = null;

            $event->save();

            DB::commit();
            return redirect('/admin/Event')->with('success', 'Event berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan event: ' . $e->getMessage())->withInput();
        }
    }

    // Tambahkan juga fungsi destroy jika belum ada (untuk delete)
    public function hapusEvent($id)
    {
        $event = Event::where('eventID', $id)->first();

        if ($event) {
            // --- LOGIKA HAPUS GAMBAR ---
            
            // Perhatikan: Kita pakai $event->imagePath
            // Kita sambung ke storage_path karena file asli ada di storage/app/public/...
            $gambarPath = storage_path('app/public/' . $event->imagePath); 
            
            // Cek file ada & path tidak kosong
            if (!empty($event->imagePath) && File::exists($gambarPath)) {
                File::delete($gambarPath);
            }

            // --- HAPUS DATA ---
            $event->delete();
            
            return redirect('/admin/Event')->with('success', 'Event berhasil dihapus.');
        }
        
        return back()->with('error', 'Gagal menghapus event.');
    }

    // A. TAMPILKAN FORM EDIT EVENT
    // Menerima parameter $id dari route '/admin/Event/edit/{id}'
    public function tampilFormEditEvent($id)
    {
        // Cari event berdasarkan eventID
        $event = Event::where('eventID', $id)->first();

        // Jika event tidak ditemukan, kembalikan ke list atau tampilkan 404
        if (!$event) {
            return redirect('/admin/Event')->with('error', 'Event tidak ditemukan.');
        }

        // Tampilkan view edit dan kirim data $event
        // Pastikan nama folder view sesuai dengan struktur folder kamu
        return view('admin.eventAdmin.editEventAdmin', ['event' => $event]);
    }

    // B. PROSES UPDATE EVENT
    // Menerima $request (inputan form) dan $id (event mana yang diedit)
    public function editEvent(Request $request, $id)
    {
        // 1. Validasi Input
        // Mirip dengan create, tapi 'image' kita buat nullable (tidak wajib diisi)
        // Karena user mungkin hanya ingin edit teks tanpa ganti gambar
        $request->validate([
            'name'        => 'required',
            'location'    => 'required',
            'description' => 'required',
            'startDate'   => 'required|date',
            'endDate'     => 'required|date|after_or_equal:startDate',
            'startTime'   => 'required',
            'endTime'     => 'required',
            'entranceFee' => 'required|numeric',
            'socialMedia' => 'required',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        DB::beginTransaction();
        try {
            // Cari event yang akan diedit
            $event = Event::where('eventID', $id)->first();

            if (!$event) {
                return back()->with('error', 'Event tidak ditemukan.');
            }

            // 2. Logika Update Gambar (Pengganti Logika Password di Profil)
            // Cek apakah user mengupload file gambar baru?
            if ($request->hasFile('image')) {
                
                // A. Hapus Gambar Lama (Jika ada file fisiknya)
                // Kita gunakan disk 'public' sesuai settingan saat store
                if ($event->imagePath && Storage::disk('public')->exists($event->imagePath)) {
                    Storage::disk('public')->delete($event->imagePath);
                }

                // B. Upload Gambar Baru
                // Simpan ke folder 'events' di dalam disk 'public'
                $path = $request->file('image')->store('events', 'public');
                
                // C. Update path di database
                $event->imagePath = $path;
            }

            // 3. Update Data Teks & Tanggal
            $event->name        = $request->name;
            $event->location    = $request->location;
            $event->description = $request->description;
            $event->entranceFee = $request->entranceFee;
            $event->socialMedia = $request->socialMedia;
            
            $event->startDate   = $request->startDate;
            $event->endDate     = $request->endDate;
            $event->startTime   = $request->startTime;
            $event->endTime     = $request->endTime;

            // Simpan perubahan
            $event->save();

            DB::commit();

            // 4. Redirect kembali ke halaman list event
            return redirect('/admin/Event')->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update event: ' . $e->getMessage())->withInput();
        }
    }
}
