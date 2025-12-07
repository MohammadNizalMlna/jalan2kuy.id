<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestCategory extends Model
{
    protected $table = 'destcategory';
    protected $primaryKey = 'destCategoryID';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'destCategoryID',
        'categoryName',
    ];

    public $timestamps = false; 
}
