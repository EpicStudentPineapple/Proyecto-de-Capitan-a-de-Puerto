<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muelle extends Model
{
    use HasFactory;

    protected $table = 'muelles';

    protected $fillable = [
        'nombre',
        'codigo',
        'longitud',
        'calado_maximo',
        'capacidad_toneladas',
        'tipo_muelle',
        'disponible',
        'grua_disponible',
        'energia_tierra',
        'observaciones',
    ];

    protected $casts = [
        'longitud' => 'decimal:2',
        'calado_maximo' => 'decimal:2',
        'capacidad_toneladas' => 'integer',
        'disponible' => 'boolean',
        'grua_disponible' => 'boolean',
        'energia_tierra' => 'boolean',
    ];

    public function buques()
    {
        return $this->hasMany(Buque::class);
    }

    public function pantalans()
    {
        return $this->hasMany(Pantalan::class);
    }

    public function buqueActual()
    {
        return $this->hasOne(Buque::class)->where('estado', 'atracado');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_muelle', $tipo);
    }

    public function estaOcupado()
    {
        return $this->buques()->where('estado', 'atracado')->exists();
    }

    public function puedeAtracar(Buque $buque)
    {
        return $this->disponible 
            && !$this->estaOcupado()
            && $buque->calado <= $this->calado_maximo
            && $buque->eslora <= $this->longitud;
    }
}