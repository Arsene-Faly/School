<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAbout extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolAboutFactory> */
    use HasFactory;

    protected $fillable = [
        'school_id',
        'description',
        'mission',
        'vision',
        'history',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
