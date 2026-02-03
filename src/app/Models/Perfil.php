<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfils';

    protected $fillable = [
        'user_id',
        'tipo_usuario',
        'telefono',
        'empresa',
        'cargo',
        'licencia_maritima',
        'fecha_alta',
        'activo',
    ];

    protected $casts = [
        'fecha_alta' => 'date',
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_usuario', $tipo);
    }
}
