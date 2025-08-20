<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    /** @use HasFactory<\Database\Factories\FormationFactory> */
    use HasFactory;

    protected $fillable = ['school_id', 'title', 'description', 'slug', 'cover_photo', 'status'];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function views()
    {
        return $this->hasMany(FormationView::class);
    }
}
