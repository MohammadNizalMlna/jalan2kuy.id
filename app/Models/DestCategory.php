<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestCategory extends Model {
    protected $table = 'destcategory'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'destCategoryID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string
    public $timestamps = false; //timestamps false karena tidak ada kolom created_at, updated_at dalam tabel destcategory

    //atribut atau kolom yang ada pada tabel destcategory
    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        'destCategoryID',
        'categoryName',
        'categoryImage',
    ];
}
