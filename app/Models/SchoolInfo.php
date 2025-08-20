<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolInfoFactory> */
    use HasFactory;

    protected $fillable = [
        'school_id',
        'logo',
        'cover_photo',
        'latitude',
        'longitude',
        'description',
        'date_creation',
        'nombre_etudiants',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
