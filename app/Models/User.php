<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use HasPanelShield;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'is_admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function canAccessFilament(): bool
    {
        return $this->is_admin; // أو شرط آخر يناسبك
    }
    
    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->is_admin) {
                $user->assignRole('super_admin');
                $user->removeRole('user'); // إزالة دور المستخدم إذا كان مديرًا
            } else {
                $user->assignRole('user');
                $user->removeRole('super_admin'); // إزالة دور المدير إذا كان مستخدمًا عاديًا
            }
        });
    }
}
