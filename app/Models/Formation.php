<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    /** @use HasFactory<\Database\Factories\FormationFactory> */
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'slug',
        'cover_photo',
        'status',
        'duration',
        'mode',
        'language',
        'price',
        'category', // pour stocker les catégories (array ou JSON)
        'level',   // pour stocker les niveaux (array ou JSON)
    ];


    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function views()
    {
        return $this->hasMany(FormationView::class);
    }
}
