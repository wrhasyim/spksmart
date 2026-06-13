<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['student_id', 'scores_data'];

    protected $casts = [
        'scores_data' => 'array', // Otomatis mengurai JSON menjadi Array PHP
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}