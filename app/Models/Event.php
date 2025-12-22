<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    protected $table = 'event'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'eventID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string
    public $timestamps = false; //timestamps false karena tidak ada kolom created_at, updated_at dalam tabel event

    //atribut atau kolom yang ada pada tabel event
    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
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

    //casting tipe data agar sesuai kebutuhan
    protected $casts = [
        'entranceFee' => 'integer',
        'startDate' => 'date',
        'endDate'   => 'date',
    ];
}