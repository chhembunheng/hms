<?php

namespace App\Models\Settings;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\Loggable;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use SoftDeletes, HasFactory, Notifiable, Loggable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'username',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'description',
        'remember_token',
        'email_verified_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function getNameAttribute()
    {
        $tran = $this->translations()->where('locale', app()->getLocale())->first();
        if ($tran && !empty($tran->first_name)) {
            return trim($tran->last_name . ' ' . $tran->first_name);
        }
        // Fallback to English if current locale not found
        $tranEn = $this->translations()->where('locale', 'en')->first();
        if ($tranEn && !empty($tranEn->first_name)) {
            return trim($tranEn->last_name . ' ' . $tranEn->first_name);
        }
        return 'User'; // Default fallback
    }

     public function translations()
    {
        return $this->hasMany(UserTranslation::class, 'user_id');
    }
}
