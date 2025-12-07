<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    // ==========================================
    // 1. KONFIGURASI TABEL & PRIMARY KEY
    // ==========================================

    /**
     * Nama tabel di database.
     * Default Laravel: 'destinations' (jamak).
     * Jika nama tabel Anda tunggal ('destination'), aktifkan baris di bawah ini:
     */
    protected $table = 'destination';

    /**
     * Menentukan Primary Key.
     * Default Laravel: 'id'. Kita ubah jadi 'destinationID'.
     */
    protected $primaryKey = 'destinationID';

    /**
     * Matikan auto-increment karena ID berupa String, bukan Integer.
     */
    public $incrementing = false;

    /**
     * Tentukan tipe data Primary Key adalah string.
     */
    protected $keyType = 'string';

    /**
     * Matikan timestamps (created_at, updated_at) jika di tabel tidak ada.
     */
    public $timestamps = false;

    // ==========================================
    // 2. MASS ASSIGNMENT (FILLABLE)
    // ==========================================
    
    /**
     * Daftar kolom yang boleh diisi datanya secara massal.
     * Sesuaikan nama kolom ini PERSIS dengan nama kolom di database Anda.
     */
    protected $fillable = [
        'destinationID',
        'name',
        'location',
        'description',
        'entranceFee',
        'openingDay',
        'closingDay',
        'openingHours',
        'closingHours',
        'imagePath',
        'thumbnailImagePath',
    ];

    // ==========================================
    // 3. CASTING TIPE DATA
    // ==========================================

    /**
     * Mengubah format data dari database ke tipe data PHP yang sesuai.
     */
    protected $casts = [
        // 'entranceFee' otomatis jadi integer, tidak perlu di-cast khusus kecuali ingin format lain.
        'entranceFee' => 'integer',

        // Mengubah 'openingDay' & 'closingDay' (Java Date) menjadi instance Carbon (Date)
        // Ini memungkinkan Anda memformat tanggal dengan mudah di Blade: $dest->openingDay->format('d F Y')
        'openingDay' => 'date',
        'closingDay' => 'date',
        
        // Untuk 'openingHours' & 'closingHours' (Java LocalTime / SQL TIME):
        // Laravel biasanya membacanya sebagai String (contoh: "08:00:00").
        // Jika ingin diubah jadi objek waktu, bisa gunakan 'datetime' (tapi akan ada tanggal dummy).
        // Saran: Biarkan string atau gunakan custom accessor jika butuh format khusus.
    ];
}