<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityView extends Model
{
    protected $fillable = [
        'activity_id',
        'user_id',
        'ip_address',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
