<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nisn', 
        'name', 
        'class_name', 
        'major_id', 
        'gender', 
        'parent_phone', 
        'status', 
        'final_score', 
        'academic_year_id'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
}