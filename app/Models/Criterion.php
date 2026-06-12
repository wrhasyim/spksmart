<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    // Tambahkan baris ini agar model membaca tabel 'criterias' yang dibuat migrasi
    protected $table = 'criterias'; 

    protected $fillable = ['code', 'name', 'weight', 'type'];
}