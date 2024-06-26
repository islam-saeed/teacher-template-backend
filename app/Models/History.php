<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    
    protected $fillable = ['model_id', 'action', 'data','admin_id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

}
