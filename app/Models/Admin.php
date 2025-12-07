<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // --- Konfigurasi Tabel (Sama seperti sebelumnya) ---
    protected $table = 'admin';
    protected $primaryKey = 'adminID';
    public $incrementing = false; 
    protected $keyType = 'string';

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