<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
            $event->delete();
            return redirect('/admin/Event')->with('success', 'Event berhasil dihapus.');
        }
        
        return back()->with('error', 'Gagal menghapus event.');
    }
}
