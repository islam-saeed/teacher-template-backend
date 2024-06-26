<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'admin_id',
        'description',
        'card_media',
        
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }


}
