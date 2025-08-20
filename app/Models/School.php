<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'category_id',
        'nif',
        'stat',
        'adresse',
        'contact',
        'document',
        "user_id",
        "status",
        "type",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function info()
    {
        return $this->hasOne(SchoolInfo::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('etoile') ?? 0;
    }

    public function galeryImages()
    {
        return $this->hasMany(GalerySchool::class, 'school_id');
    }

    public function Schooltestimonials()
    {
        return $this->hasMany(SchoolTestimonial::class);
    }

    public function formations()
    {
        return $this->hasMany(formation::class, 'school_id');
    }

    public function Activity()
    {
        return $this->hasMany(Activity::class, 'school_id');
    }

    public function about()
    {
        return $this->hasOne(SchoolAbout::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'school_id', 'user_id')
            ->withTimestamps();
    }
}
