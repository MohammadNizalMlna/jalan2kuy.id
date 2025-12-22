<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Destination; 
use App\Models\DestCategory;
use App\Models\Event;

class DestinationController extends Controller { //penamaan controller menggunakan huruf kapital pada awal masing-masing kata
    //function untuk menampilkan form addDestination
    public function addDestination(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //ambil semua data event untuk ditampilkan pada dropdown 
        $events = Event::all(); //penamaan variabel diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        
        //redirect ke halaman addDestination dengan membawa data $events
        return view('admin.destination.addDestination', ['events' => $events]);
    }

    //function untuk menyimpan data destination yang diinputkan pada form addDestination
    public function storeDestinationData(Request $request) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //Validasi Input
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
            'destCategoryID' => 'required',
        ]);

        //Memulai Transaction ke database
        DB::beginTransaction();
        try {
            //generate destinationID baru (ID-ID terdiri dari 6 karakter dengan 3 karakter pertama adalah alfabet dan 3 karakter sisanya adalah angka (mengurut))
            $lastDestination = Destination::orderBy('destinationID', 'desc')->lockForUpdate()->first(); //ambil destinationID terakhir yang ada pada tabel destination di database
            $newDestinationID = 'dst001'; //jika tidak ditemukan destinationID terakhir, gunakan $newDestinationID

            if ($lastDestination) { //jika ditemukan destinationID terakhir, maka generate destinationID baru
                $number = (int) substr($lastDestination->destinationID, 3);
                $number++;
                $newDestinationID = 'dst' . sprintf("%03d", $number);
            }

            //logika upload gambar (image)
            $imagePath = null; //set variabel $imagePath ke NULL
            //cek apakah user mengupload image atau tidak
            if ($request->hasFile('image')) { //jika user mengupload image
                $imagePath = $request->file('image')->store('destinations/image', 'public'); //simpan image tersebut kedalam folder storage/app/public/destinations/image menggunakan disk 'public', simpan string path nya kedalam variabel $imagePath
            }
            
            //logika upload gambar thumbnail (thumbnailImage)
            $thumbnailImagePath = null; //set variabel $thumbnailImagePath ke NULL
            //cek apakah user mengupload thumbnail image atau tidak
            if ($request->hasFile('thumbnailImage')) { //jika user mengupload thumbnail image
                $thumbnailImagePath = $request->file('thumbnailImage')->store('destinations/thumbnailImage', 'public'); //simpan thumbnail image tersebut kedalam folder storage/app/public/destinations/thumbnailImage menggunakan disk 'public', simpan string path nya kedalam variabel $thumbnailImagePath
            }

            //buat objek destination dan simpan datanya ke database
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
            $destination->adminID = Session::get('admin_id'); //foreign key adminID mengambil dari admin_id yang ada di session
            //ambil data destCategory yang ID nya sesuai dengan $request->destCategoryID, simpan kedalam variabel $kategori
            $kategori = DestCategory::find($request->destCategoryID);
            if ($kategori) { //jika $kategori ditemukan
                $destination->destCategoryID = $kategori->destCategoryID; //foreign key desCategoryID pada tabel destination mengambil nilai dari $kategori->destCategoryID
            } else {
                //jika destCategoryID tidak ditemukan, tampilkan pesan error
                return back()->with('error', 'Kategori tidak valid!');
            }
            $destination->save();

            //Logika simpan event yang terkait ke destination
            // Cek apakah ada input eventID dan pastikan dia Array
            if ($request->filled('eventID') && is_array($request->eventID)) {
                //looping setiap eventID yang dipilih (jadikan sebagai $singleEventID)
                foreach ($request->eventID as $singleEventID) {
                    if (!empty($singleEventID)) { //jika $singleEventID tidak kosong
                        $relatedEvent = Event::find($singleEventID); //cari data event yang eventID nya sesuai dengan $singleEventID
                        if ($relatedEvent) { //Jika data event ketemu, update foreign key destinationID-nya dengan $destination->destinationID
                            $relatedEvent->destinationID = $destination->destinationID;
                            $relatedEvent->save();
                        }
                    }
                }
            }

            //commit transaction ke database
            DB::commit();

            //redirect ke halaman destination dimana categorynya sesuai dengan $kategori->destCategoryID (/admin/Destination/Category?Category=(destCategoryID nya))
            return redirect('/admin/Destination/Category?Category=' . $kategori->destCategoryID)->with('success', 'Destinasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            //Jika ada yang salah, rollback transaction
            DB::rollBack();
            //kembalikan ke form addDestination dengan pesan error
            return back()->with('error', 'Gagal menyimpan destination: ' . $e->getMessage())->withInput();
        }
    }

    //function untuk menampilkan detail destination pada halaman destination admin
    public function tampilkanDetailDestinationAdmin($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //ambil data destination dimana destinationID nya sesuai dengan $id (query), simpan kedalam $destination
        $destination = Destination::where('destinationID', $id)->first();

        if (!$destination) { //jika $destination tidak ketemu
            //redirect ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }

        //ambil data Event yang foreign key destinationID-nya sama dengan $id, simpan kedalam $relatedEvents
        $relatedEvents = Event::where('destinationID', $id)->get();

        //redirect ke halaman detailDestinationAdmin dengan membawa data $destination dan $relatedEvents
        return view('admin.destination.detailDestinationAdmin', ['destination' => $destination, 'events' => $relatedEvents]);
    }

    //function untuk mengahpus destination tertentu 
    public function deleteDestination($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //ambil data destination dimana destinationID nya sesuai dengan $id (query), simpan kedalam $destination
        $destination = Destination::where('destinationID', $id)->first();
        
        if ($destination) { //jika $destination ketemu
            //lepaskan atau un-assign relasi event - destination
            //cari semua event yang foreign key destinationID nya sesuai dengan $id, kemudian ubah foreign key destinationID nya menjadi NULL
            Event::where('destinationID', $id)->update(['destinationID' => null]);

            //hapus file image yang path-nya berdasarkan $destination->imagePath
            if ($destination->imagePath) Storage::disk('public')->delete($destination->imagePath);

            //hapus file thumbnail image yang path-nya berdasarkan $destination->thumbnailImagePath
            if ($destination->thumbnailImagePath) Storage::disk('public')->delete($destination->thumbnailImagePath);

            //hapus destination nya
            $destination->delete();

            //redirect ke halaman destination dimana categorynya sesuai dengan $destination->destCategoryID (/admin/Destination/Category?Category=(destCategoryID nya))
            return redirect('/admin/Destination/Category?Category=' . $destination->destCategoryID)->with('success', 'Destinasi berhasil dihapus (Event terkait telah di-unassign).');
        }

        //jika $destination ngga ketemu, kembali ke halaman sebelumnya dengan pesan error
        return back()->with('error', 'Gagal menghapus data.');
    }

    //function untuk menampilkan form edit destination
    public function tampilFormEditDestination($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //ambil data destination dimana destinationID nya sesuai dengan $id (query), simpan kedalam $destination
        $destination = Destination::where('destinationID', $id)->first();
        
        if (!$destination) { //jika $destination tidak ketemu
            //redirect ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Destinasi tidak ditemukan.');
        }

       //ambil semua data event untuk ditampilkan pada dropdown 
        $allEvents = Event::all(); 

        //ambil event milik destination yang mau di-edit
        //cari di tabel Event, yang foreign key destinationID-nya sesuai dengan $id, simpan kedalam $currentEvents
        $currentEvents = Event::where('destinationID', $id)->get();
        
        //redirect ke halaman editDestinationAdmin dengan membawa data $destination, $allEvents (semua event), dan $currentEvents (event-event milik destination yang mau di-edit)
        return view('admin.destination.editDestinationAdmin', ['destination' => $destination, 'events' => $allEvents, 'currentEvents' => $currentEvents]);
    }

    //function untuk menyimpan hasil edit data destination yang diinputkan pada form editDestination
    public function editDestination(Request $request, $id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }

        //validasi inputan
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
            'image' => 'nullable|image|max:10240', 
            'thumbnailImage' => 'nullable|image|max:10240',
        ]);

        try {
            //ambil data destination yang destinationID nya sesuai dengan $id, simpan kedalam $destination
            $destination = Destination::where('destinationID', $id)->first();
            
            //logika update Gambar (Hanya jika ada user menguploadkan gambar baru)
            //cek apakah user mengupload image baru atau tidak
            if ($request->hasFile('image')) { //jika user mengupload image baru
                if ($destination->imagePath) Storage::disk('public')->delete($destination->imagePath); //delete image yang sebelumnya tersimpan pada folder storage/app/public/destinations/image
                $destination->imagePath = $request->file('image')->store('destinations/image', 'public'); //simpan image baru yang diupload user kedalam folder storage/app/public/destinations/image menggunakan disk 'public', simpan string path nya kedalam $destination->imagePath
            }
            //cek apakah user mengupload thumbnail image baru atau tidak
            if ($request->hasFile('thumbnailImage')) { //jika user mengupload thumbnail image baru
                if ($destination->thumbnailImagePath) Storage::disk('public')->delete($destination->thumbnailImagePath); //delete thumbanil image yang sebelumnya tersimpan pada folder storage/app/public/destinations/thumbnailImage
                $destination->thumbnailImagePath = $request->file('thumbnailImage')->store('destinations/thumbnailImage', 'public'); //simpan thumbnail image baru yang diupload user kedalam folder storage/app/public/destinations/thumbnailImage menggunakan disk 'public', simpan string path nya kedalam $destination->thumbnailImagePath
            }

            //update data destination lainnya sesuai ayng diinputkan user pada form editDestinationAdmin dan simpan datanya ke database
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

            //logika update event (re-assign)
            //cari semua event yang foreign key destinationID nya sesuai dengan $id, kemudian ubah foreign key destinationID nya menjadi NULL
            Event::where('destinationID', $id)->update(['destinationID' => null]);
            //pasang kembali event-event tersebut ke destination yang sedang di-edit
            if ($request->filled('eventID') && is_array($request->eventID)) { //Cek apakah ada input eventID dan pastikan dia Array
                //looping setiap eventID yang dipilih (jadikan sebagai $singleEventID)
                foreach ($request->eventID as $singleEventID) {
                    if (!empty($singleEventID)) { //jika $singleEventID tidak kosong
                        $event = Event::find($singleEventID); //cari data event yang eventID nya sesuai dengan $singleEventID
                        if ($event) { //jika event tersebut ketemu
                            $event->destinationID = $id; //ubah foreign key destinationID nya dengan $id
                            $event->save();
                        }
                    }
                }
            }

            //redirect ke halaman detail destination berdasarkan $id nya (/admin/Destination/Detail/$id)
            return redirect('/admin/Destination/Detail/' . $id)->with('success', 'Destinasi berhasil diperbarui!');
        } catch (\Exception $e) {
            //jika terjadi kesalahan, redirect ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    //function untuk menampilkan detail destination pada halaman destination
    public function tampilkanDetailDestination($id) { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //ambil data destination dimana destinationID nya sesuai dengan $id (query), simpan kedalam $destination
        $destination = Destination::where('destinationID', $id)->first();

        if (!$destination) { //jika $destination tidak ketemu
            //redirect ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }

        //ambil data Event yang foreign key destinationID-nya sama dengan $id, simpan kedalam $relatedEvents
        $relatedEvents = Event::where('destinationID', $id)->get();

        //redirect ke halaman detailDestination dengan membawa data $destination dan $relatedEvents
        return view('destination.detailDestination', ['destination' => $destination, 'events' => $relatedEvents]);
    }

    //function untuk menampilkan galeri pada user biasa
    public function tampilGaleri() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        $destinations = Destination::all(); //Ambil semua data destination dari database, simpan kedalam $destinations
        //redirect ke halaman gallery dengan membawa data $destinations
        return view('gallery.gallery', ['destinations' => $destinations]);
    }

    //function untuk menampilkan galeri pada user admin
    public function tampilGaleriAdmin() { //penamaan function diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        //Cek apakah user sudah melewati tahap login awal atau belum
        if (!Session::has('admin_id')) {
            return redirect('/login')->with('error', 'Anda harus login dulu!');
        }
        
        $destinations = Destination::all(); //Ambil semua data destination dari database, simpan kedalam $destinations
        //redirect ke halaman galleryAdmin dengan membawa data $destinations
        return view('admin.gallery.galleryAdmin', compact('destinations'));
    }
}