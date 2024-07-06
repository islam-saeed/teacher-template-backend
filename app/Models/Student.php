<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'attendance',
        'date_of_absence',
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($student) {
            $student->createHistoryRecord('create student');
        });

        static::updated(function ($student) {
            $student->createHistoryRecord('update student');
        });

        static::deleted(function ($student) {
            $student->createHistoryRecord('delete student');
        });
    }

    public function createHistoryRecord($action)
    {
        History::create([
            'model_id' => $this->id,
            'action'   => $action,
            'data'     => $this->toJson(),
            'admin_id' => Auth::id(),
        ]);
    }


    
}
