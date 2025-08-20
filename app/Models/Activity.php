<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'slug',
        'cover_photo',
        'description',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function views()
    {
        return $this->hasMany(ActivityView::class);
    }
}
