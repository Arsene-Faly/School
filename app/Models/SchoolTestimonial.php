<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTestimonial extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolTestimonialFactory> */
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        "content"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
