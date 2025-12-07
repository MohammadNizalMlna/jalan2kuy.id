<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // ==========================================
    // 1. KONFIGURASI TABEL & PRIMARY KEY
    // ==========================================

    /**
     * Nama tabel di database.
     * Default Laravel: 'events'.
     * Jika nama tabel Anda tunggal ('event'), aktifkan baris di bawah ini:
     */
    protected $table = 'event';

    /**
     * Menentukan Primary Key.
     * Karena di Java Anda pakai 'eventID', kita set manual di sini.
     */
    protected $primaryKey = 'eventID';

    /**
     * Matikan auto-increment karena ID berupa String.
     */
    public $incrementing = false;

    /**
     * Tentukan tipe data Primary Key adalah string.
     */
    protected $keyType = 'string';

    /**
     * Matikan timestamps (created_at, updated_at) karena tidak ada di class Java.
     */
    public $timestamps = false;

    // ==========================================
    // 2. MASS ASSIGNMENT (FILLABLE)
    // ==========================================

    /**
     * Daftar kolom yang boleh diisi datanya secara massal.
     */
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

    /**
     * Mengubah format data database menjadi tipe data PHP native.
     */
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