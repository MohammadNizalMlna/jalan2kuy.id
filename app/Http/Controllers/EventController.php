<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Event; 

class EventController extends Controller {

    public function addEvent() {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //redirect ke view addEvent
        return view('admin.eventAdmin.addEvent');
    }

    public function storeEventData(Request $request) {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //validasi input
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

        //Memulai Transaction
        DB::beginTransaction();
        try {
            //generate eventID baru
            $lastEvent = Event::orderBy('eventID', 'desc')->lockForUpdate()->first();
            $newEventID = 'evt001';

            if ($lastEvent) {
                $number = (int) substr($lastEvent->eventID, 3);
                $number++;
                $newEventID = 'evt' . sprintf("%03d", $number);
            }

            //Logika Upload Gambar (yang disimpan pada database adalah imagePath, gambar asli disimpan didalam folder storage/app/public/events)
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('events', 'public');
            }

            //buat objek Event dan siimpan datanya ke database
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
            $event->imagePath = $imagePath;
            $event->adminID = Session::get('admin_id'); //foreign key adminID mengambil datanya dari session
            $event->destinationID = null; //foreign key destinationID defaultnya di set NULL
            $event->save();

            //commit transaction
            DB::commit();

            return redirect('/admin/Event')->with('success', 'Event berhasil ditambahkan!');
        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan event: ' . $e->getMessage())->withInput();
        }
    }

    public function tampilEvent(Request $request) { //menampilan semua event (untuk user biasa)
        //mulai query
        $query = Event::query();

        //Logika searching berdasarkan tanggal (Jika user mengisi input tanggal)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('startDate', [
                $request->input('start_date'), 
                $request->input('end_date')
            ]);
        }

        //Ambil data event (urutkan dari yang terbaru)
        $events = $query->orderBy('startDate', 'desc')->get();

        //kirim data $events ke view event
        return view('event.event', ['events' => $events]);
    }

    public function tampilkanDetailEvent($id) { 
        //cari data event berdasarkan eventID
        $event = Event::where('eventID', $id)->first();

        //Jika tidak ketemu, kembalikan ke URL /Event
        if (!$event) {
            return redirect('/Event')->with('error', 'Event tidak ditemukan.');
        }

        //kirim data $event ke view detailEvent
        return view('event.detailEvent', ['event' => $event]);
    }

    //===FUNCTION-FUNCTION DIBAWAH INI KHUSUS ADMIN ONLY===

    public function tampilEventAdmin(Request $request) {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //mulai query
        $query = Event::query();

        //Logika searching berdasarkan tanggal (Jika user mengisi input tanggal)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('startDate', [
                $request->input('start_date'), 
                $request->input('end_date')
            ]);
        }

        //Ambil data event (urutkan dari yang terbaru)
        $events = $query->orderBy('startDate', 'desc')->get();

        //kirim data $events ke view eventAdmin
        return view('admin.eventAdmin.eventAdmin', ['events' => $events]);
    }

    public function tampilkanDetailEventAdmin($id) { 
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //cari data event berdasarkan eventID
        $event = Event::where('eventID', $id)->first();

        //Jika tidak ketemu, kembalikan ke URL /admin/Event
        if (!$event) {
            return redirect('/admin/Event')->with('error', 'Event tidak ditemukan.');
        }

        //kirim data $event ke view detailEventAdmin
        return view('admin.eventAdmin.detailEventAdmin', ['event' => $event]);
    }

    public function tampilFormEditEvent($id) {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        //cari data event berdasarkan eventID
        $event = Event::where('eventID', $id)->first();

        // Jika event tidak ditemukan, kembalikan ke list atau tampilkan 404
        if (!$event) {
            return redirect('/admin/Event')->with('error', 'Event tidak ditemukan.');
        }

        //kirim data $event ke view editEventAdmin
        return view('admin.eventAdmin.editEventAdmin', ['event' => $event]);
    }

    public function editEvent(Request $request, $id) {
        //cek di session apakah sudah ada admin_id yang login atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        
        //validasi input
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

        //Memulai Transaction
        DB::beginTransaction();

        try {
            //cari event yang mau diedit berdasarkan eventID
            $event = Event::where('eventID', $id)->first();

            //kalo ngga ketemu tampilkan teks "Event tidak ditemukan"
            if (!$event) {
                return back()->with('error', 'Event tidak ditemukan.');
            }

            //Logika Update Gambar (cek dulu apakah user mengupload gambar baru atau tidak)
            if ($request->hasFile('image')) {
                //hapus gambar lama (cek dulu apakah gambar lama masih disimpan di storage/app/public/events atau tidak)
                if ($event->imagePath && Storage::disk('public')->exists($event->imagePath)) {
                    Storage::disk('public')->delete($event->imagePath);
                }

                //upload gambar baru dan simpan didalam folder storage/app/public/events 
                $path = $request->file('image')->store('events', 'public');
                
                //Update imagePath di database ($path dimasukkan ke imagePath)
                $event->imagePath = $path;
            }

            //Update Data lainnya dari input yang sudah divalidasi
            $event->name = $request->input('name');
            $event->location = $request->input('location');
            $event->description = $request->input('description');
            $event->entranceFee = $request->input('entranceFee');
            $event->socialMedia = $request->input('socialMedia');
            $event->startDate = $request->input('startDate');
            $event->endDate = $request->input('endDate');
            $event->startTime = $request->input('startTime');
            $event->endTime = $request->input('endTime');
            $event->save();

            //commit transaction
            DB::commit();

            //redirect kembali ke halaman /admin/Event
            return redirect('/admin/Event')->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();
            
            return back()->with('error', 'Gagal update event: ' . $e->getMessage())->withInput();
        }
    }

    // Tambahkan juga fungsi destroy jika belum ada (untuk delete)
    public function hapusEvent($id)
    {
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
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
}
