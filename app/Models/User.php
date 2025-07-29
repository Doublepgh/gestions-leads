<?php

namespace App\Models;

use App\Models\Asignacion;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'activo',
        'modo_asignacion',
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

    public function esAutomatico()
    {
        return $this->modo_asignacion === 'automatico';
    }

    protected static function booted()
    {
        static::created(function ($user) {
            if (!$user->hasAnyRole()) {
                $user->assignRole('operador');
            }
        });
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'operador_id');
    }

    public function leadsAsignados()
    {
        return $this->hasMany(Lead::class, 'operador_id');
    }

    public function operador()
{
    return $this->belongsTo(User::class, 'operador_id');
}
}
