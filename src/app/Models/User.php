<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->perfil()->create([
                'tipo_usuario' => 'propietario',
                'fecha_alta' => now(),
                'activo' => true,
            ]);
        });
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

    public function perfil()
    {
        return $this->hasOne(Perfil::class);
    }

    public function buques()
    {
        return $this->hasMany(Buque::class , 'propietario_id');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    public function isAdmin()
    {
        return $this->perfil && $this->perfil->tipo_usuario === 'administrador';
    }

    public function isAdministrador()
    {
        return $this->isAdmin();
    }

    public function isPropietario()
    {
        return $this->perfil && $this->perfil->tipo_usuario === 'propietario';
    }


}
