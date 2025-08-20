<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    public function scopeFilter($query, Request $request, string $role)
    {
        $search = $request->input('search');
        $active = $request->input('active');

        return $query->with('info')
            ->where('role', $role)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($active !== null && $active !== '', function ($query) use ($active) {
                if ($active == '1') {
                    $query->whereNotNull('email_verified_at');
                } elseif ($active == '0') {
                    $query->whereNull('email_verified_at');
                }
            })
            ->orderBy('created_at', 'desc');
    }

    public static function getRoleCounts(): array
    {
        return self::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function info(): HasOne
    {
        return $this->hasOne(UserInfo::class);
    }

    public function school(): HasOne
    {
        return $this->hasOne(School::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function contact()
    {
        return $this->hasMany(Contact::class);
    }

    public function Schooltestimonials()
    {
        return $this->hasMany(SchoolTestimonial::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(School::class, 'favorites', 'user_id', 'school_id')
            ->withTimestamps()
            ->withPivot('id');
    }
}
