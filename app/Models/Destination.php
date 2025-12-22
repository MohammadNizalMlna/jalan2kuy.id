<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model {
    protected $table = 'destination'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'destinationID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string
    public $timestamps = false; //timestamps false karena tidak ada kolom created_at, updated_at dalam tabel destination

    //atribut atau kolom yang ada pada tabel destination
    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        'destinationID',
        'name',
        'location',
        'description',
        'entranceFee',
        'openingDay',
        'closingDay',
        'openingHours',
        'closingHours',
        'timezone',
        'imagePath',
        'thumbnailImagePath',
    ];

    //casting tipe data agar sesuai kebutuhan
    protected $casts = [
        'entranceFee' => 'integer',
    ];
}