<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criterion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'weight',
        'type' // Enum: 'benefit' atau 'cost'
    ];
}