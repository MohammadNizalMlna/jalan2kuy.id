<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    //konfigurasi tabel admin dan primary key adminID
    //kolom primary ke selalu diawali dengan nama tabelnya + ID, dan bertipe data string
    protected $table = 'admin';
    protected $primaryKey = 'adminID';
    public $incrementing = false; 
    protected $keyType = 'string';

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

    protected $casts = [
        'gender' => 'boolean',
    ];

    public $timestamps = false; 
}