<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    // use HasFactory;

    //konfigurasi tabel admin dan primary key adminID
    //kolom primary ke selalu diawali dengan nama tabelnya + ID dan bertipe data string (incrementing false karena string)
    //timestamps false karena tidak ada kolom created_at, updated_at dalam tabel admin
    protected $table = 'admin';
    protected $primaryKey = 'adminID';
    public $incrementing = false; 
    protected $keyType = 'string';
    public $timestamps = false; 

    //atribut atau kolom yang ada pada tabel admin
    protected $fillable = [
        'adminID',
        'name',
        'username',
        'password',
        'email',
        'gender',
    ]; 

    protected $hidden = [
        'password',
    ];

    //Mengubah format data database menjadi tipe data PHP native (casting tipe data)
    // Database menyimpan data dalam format teks mentah atau angka sederhana. 
    // Tanpa casting, kamu harus mengolahnya manual setiap kali mau dipakai.
    protected $casts = [
        'gender' => 'boolean',
    ];
}