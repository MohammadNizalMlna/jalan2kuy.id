<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {
    protected $table = 'admin'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'adminID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string
    public $timestamps = false; //timestamps false karena tidak ada kolom created_at, updated_at dalam tabel admin

    //atribut atau kolom yang ada pada tabel admin
    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
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

    //casting tipe data agar sesuai kebutuhan
    protected $casts = [
        'gender' => 'boolean',
    ];
}