<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'days',
        'time_period',
        'color',
        'notes',
        'group',
        'admin_id',
        'fees',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::created(function ($section) {
            $section->createHistoryRecord('created');
        });

        static::updated(function ($section) {
            $section->createHistoryRecord('updated');
        });

        static::deleted(function ($section) {
            $section->createHistoryRecord('deleted');
        });
    }

    public function createHistoryRecord($action)
    {
        History::create([
            'model_id' => $this->id,
            'action'   => $action,
            'data'     => $this->toJson(),
        ]);
    }
}
