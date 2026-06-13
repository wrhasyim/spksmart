<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Major extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function companySlots()
    {
        return $this->belongsToMany(CompanySlot::class, 'company_slot_major');
    }
}