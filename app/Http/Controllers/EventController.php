<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Event; 

class EventController extends Controller { //penamaan controller menggunakan huruf kapital pada awal masing-masing kata
    public function tampilEvent(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //mulai query
        $query = Event::query(); //penamaan variabel diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)

        //Logika searching berdasarkan tanggal (Jika user mengisi input tanggal) (query)
        if ($request->filled('start_date') && $request->filled('end_date')) { 
            $query->whereBetween('startDate', [$request->input('start_date'), $request->input('end_date')]);
        }

        //Ambil data event (urutkan dari yang terbaru), simpan kedalam $events
        $events = $query->orderBy('startDate', 'desc')->get(); 

        //redirect ke halaman event dengan membawa data $events
        return view('event.event', ['events' => $events]);
    }

    public function tampilkanDetailEvent($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //cari data event yang eventID nya sesuai dengan $id
        $event = Event::where('eventID', $id)->first();

        if (!$event) { //jika $event tidak ketemu
            //redirewct ke URL event (/Event) dengan pesan error
            return redirect('/Event')->with('error', 'Event tidak ditemukan.');
        }

        //jika $event ketemu, redirect ke halaman detailEvent dengan membawa data $event
        return view('event.detailEvent', ['event' => $event]);
    }

    //===FUNCTION-FUNCTION DIBAWAH INI KHUSUS ADMIN ONLY===

    public function addEvent() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //redirect ke halaman addEvent
        return view('admin.eventAdmin.addEvent');
    }

    public function storeEventData(Request $request) {//penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        //Memulai Transaction ke database
        DB::beginTransaction();
        try {
            //generate eventID baru (ID-ID terdiri dari 6 karakter dengan 3 karakter pertama adalah alfabet dan 3 karakter sisanya adalah angka (mengurut))
            $lastEvent = Event::orderBy('eventID', 'desc')->lockForUpdate()->first(); //ambil eventID terakhir yang ada pada tabel event di database
            $newEventID = 'evt001'; //jika tidak ditemukan eventID terakhir, gunakan $newEventID

            if ($lastEvent) { //jika ditemukan destinationID terakhir, maka generate destinationID baru
                $number = (int) substr($lastEvent->eventID, 3);
                $number++;
                $newEventID = 'evt' . sprintf("%03d", $number);
            }

            //logika upload gambar (image)
            $imagePath = null; //set variabel $imagePath ke NULL
            if ($request->hasFile('image')) { //jika user mengupload image
                $imagePath = $request->file('image')->store('events', 'public'); //simpan image tersebut kedalam folder storage/app/public/events menggunakan disk 'public', simpan string path nya kedalam variabel $imagePath
            }

            //buat objek event dan simpan datanya ke database
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
            $event->adminID = Session::get('admin_id'); //foreign key adminID mengambil dari admin_id yang ada di session
            $event->destinationID = null; //foreign key destinationID defaultnya di set NULL
            $event->save();

            //commit transaction ke database
            DB::commit();

            //redirect ke URL /admin/Event dengan pesan sukses
            return redirect('/admin/Event')->with('success', 'Event berhasil ditambahkan!');
        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();

            //kembalikan ke form addEvent dengan pesan error
            return back()->with('error', 'Gagal menyimpan event: ' . $e->getMessage())->withInput();
        }
    }

    public function tampilEventAdmin(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //mulai query
        $query = Event::query();

        //Logika searching berdasarkan tanggal (Jika user mengisi input tanggal) (query)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('startDate', [
                $request->input('start_date'), 
                $request->input('end_date')
            ]);
        }

        //Ambil data event (urutkan dari yang terbaru), simpan kedalam $events
        $events = $query->orderBy('startDate', 'desc')->get();

        //redirect ke halaman eventAdmin dengan membawa data $events
        return view('admin.eventAdmin.eventAdmin', ['events' => $events]);
    }

    public function tampilkanDetailEventAdmin($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //cari data event yang eventID nya sesuai dengan $id
        $event = Event::where('eventID', $id)->first();

        if (!$event) { //jika $event tidak ketemu
            //redirewct ke URL event (/admin/Event) dengan pesan error
            return redirect('/admin/Event')->with('error', 'Event tidak ditemukan.');
        }

        //jika $event ketemu, redirect ke halaman detailEventAdmin dengan membawa data $event
        return view('admin.eventAdmin.detailEventAdmin', ['event' => $event]);
    }

    public function tampilFormEditEvent($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //cari data event yang eventID nya sesuai dengan $id
        $event = Event::where('eventID', $id)->first();

        if (!$event) { //jika $event tidak ketemu
            //redirect ke URL event (/admin/Event) dengan pesan error
            return redirect('/admin/Event')->with('error', 'Event tidak ditemukan.');
        }

        //jika $event ketemu, redirect ke halaman editEventAdmin dengan membawa data $event
        return view('admin.eventAdmin.editEventAdmin', ['event' => $event]);
    }

    public function editEvent(Request $request, $id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        //Memulai Transaction ke database
        DB::beginTransaction();

        try {
            //cari data event yang eventID nya sesuai dengan $id
            $event = Event::where('eventID', $id)->first();

            //kalo ngga ketemu tampilkan teks "Event tidak ditemukan"
            if (!$event) { //jika $event tidak ketemu
                //redirect ke halaman sebelumnya dengan pesan error event tidak ditemukan
                return back()->with('error', 'Event tidak ditemukan.');
            }

             //logika update Gambar (Hanya jika ada user menguploadkan gambar baru)
            //cek apakah user mengupload image baru atau tidak
            if ($request->hasFile('image')) { //jika user mengupload image baru
                if ($event->imagePath && Storage::disk('public')->exists($event->imagePath)) { //cek apakah gambar lama masih disimpan di storage/app/public/events atau tidak
                    Storage::disk('public')->delete($event->imagePath); //jika masih tersimpan, maka hapus gambar tersebut
                }

                //simpan image baru yang diupload user kedalam folder storage/app/public/events menggunakan disk 'public', simpan string path nya kedalam $imagePath
                $imagePath = $request->file('image')->store('events', 'public');
                
                //Update imagePath di database ($imagePath dimasukkan ke $event->imagePath)
                $event->imagePath = $imagePath;
            }

            //update data event lainnya sesuai yang diinputkan user pada form editEventAdmin dan simpan datanya ke database
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

            //commit transaction ke database
            DB::commit();

            //redirect ke URL /admin/Event dengan membawa pesan sukses
            return redirect('/admin/Event')->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();

            //kembalikan ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Gagal update event: ' . $e->getMessage())->withInput();
        }
    }

    public function hapusEvent($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //cari data event yang eventID nya sesuai dengan $id
        $event = Event::where('eventID', $id)->first();

        if ($event) { //jika event ketemu
            //ambil path gambar yang disimpan di folder storage/app/public/events menggunakan fungsi storage_path, simpan kedalam $gambarPath
            $gambarPath = storage_path('app/public/' . $event->imagePath); 
            
            //cek apakah file gambar tersebut ada dan $event->imagePath tidak kosong
            if (!empty($event->imagePath) && File::exists($gambarPath)) {
                File::delete($gambarPath); //jika valid, hapus gambar tersebut (path nya ngambil dari $gambarPath)
            }

            //hapus event nya
            $event->delete();
            
            //redirect ke URL /admin/Event dengan membawa pesan sukses
            return redirect('/admin/Event')->with('success', 'Event berhasil dihapus.');
        }
        
        //redirect ke halaman sebelunya jika terjadi kesalahan dengan membawa pesan error
        return back()->with('error', 'Gagal menghapus event.');
    }
}