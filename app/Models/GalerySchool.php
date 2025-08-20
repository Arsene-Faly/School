<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalerySchool extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'image_path'];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
