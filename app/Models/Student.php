<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Student extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'section_id',
        'admin_id',
        'phone_number',
        'parent_name',
        'parent_phone_number',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    
}
