<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pantalan extends Model
{
    use HasFactory;

    protected $table = 'pantalans';

    protected $fillable = [
        'muelle_id',
        'nombre',
        'codigo',
        'numero_amarre',
        'longitud_maxima',
        'manga_maxima',
        'calado_maximo',
        'tipo_amarre',
        'agua_disponible',
        'electricidad_disponible',
        'amperaje',
        'wifi_disponible',
        'disponible',
        'precio_dia',
        'observaciones',
    ];

    protected $casts = [
        'numero_amarre' => 'integer',
        'longitud_maxima' => 'decimal:2',
        'manga_maxima' => 'decimal:2',
        'calado_maximo' => 'decimal:2',
        'amperaje' => 'integer',
        'agua_disponible' => 'boolean',
        'electricidad_disponible' => 'boolean',
        'wifi_disponible' => 'boolean',
        'disponible' => 'boolean',
        'precio_dia' => 'decimal:2',
    ];

    public function muelle()
    {
        return $this->belongsTo(Muelle::class);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeTipoAmarre($query, $tipo)
    {
        return $query->where('tipo_amarre', $tipo);
    }

    public function puedeAmarrar($eslora, $manga, $calado)
    {
        return $this->disponible
            && $eslora <= $this->longitud_maxima
            && ($this->manga_maxima === null || $manga <= $this->manga_maxima)
            && $calado <= $this->calado_maximo;
    }
}
