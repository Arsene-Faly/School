<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormationView extends Model
{
    protected $fillable = [
        'formation_id',
        'user_id',
        'ip_address',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
