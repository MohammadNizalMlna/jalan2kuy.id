<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // use HasFactory;

    //konfigurasi tabel event dan primary key eventID
    //kolom primary ke selalu diawali dengan nama tabelnya + ID dan bertipe data string (incrementing false karena string)
    //timestamps false karena tidak ada kolom created_at, updated_at dalam tabel event
    protected $table = 'event';
    protected $primaryKey = 'eventID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    //atribut atau kolom yang ada pada tabel event
    protected $fillable = [
        'eventID',
        'name',
        'location',
        'entranceFee',
        'description',
        'startDate',
        'endDate',
        'startTime',
        'endTime',
        'imagePath',
        'socialMedia',
    ];

    // ==========================================
    // 3. CASTING TIPE DATA
    // ==========================================


    //Mengubah format data database menjadi tipe data PHP native (casting tipe data)
    protected $casts = [
        'entranceFee' => 'integer',

        // Mengubah startDate & endDate menjadi instance Carbon (Date Object)
        // Memudahkan format tanggal di view: $event->startDate->format('d M Y')
        'startDate' => 'date',
        'endDate'   => 'date',

        // Untuk startTime & endTime (LocalTime), Laravel membacanya sebagai string 'H:i:s'
        // Anda juga bisa meng-cast ke 'datetime', tapi akan muncul tanggal dummy hari ini.
        // Saran: Biarkan string agar sesuai format waktu MySQL (TIME).
    ];
}