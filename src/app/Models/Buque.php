<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buque extends Model
{
    use HasFactory;

    protected $table = 'buques';

    protected $fillable = [
        'nombre',
        'imo',
        'mmsi',
        'bandera',
        'eslora',
        'manga',
        'calado',
        'tonelaje_bruto',
        'tipo_buque',
        'propietario_id',
        'muelle_id',
        'fecha_atraque',
        'fecha_salida_prevista',
        'estado',
        'carga_actual',
        'tripulacion',
        'observaciones',
    ];

    protected $casts = [
        'eslora' => 'decimal:2',
        'manga' => 'decimal:2',
        'calado' => 'decimal:2',
        'tonelaje_bruto' => 'integer',
        'carga_actual' => 'integer',
        'tripulacion' => 'integer',
        'fecha_atraque' => 'datetime',
        'fecha_salida_prevista' => 'datetime',
    ];

    public function propietario()
    {
        return $this->belongsTo(User::class, 'propietario_id');
    }

    public function muelle()
    {
        return $this->belongsTo(Muelle::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'buque_servicio')
            ->withPivot([
                'fecha_solicitud',
                'fecha_inicio',
                'fecha_fin',
                'estado',
                'cantidad',
                'precio_total',
                'observaciones'
            ])
            ->withTimestamps();
    }

    public function scopeAtracados($query)
    {
        return $query->where('estado', 'atracado');
    }

    public function scopeNavegando($query)
    {
        return $query->where('estado', 'navegando');
    }

    public function scopeFondeados($query)
    {
        return $query->where('estado', 'fondeado');
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_buque', $tipo);
    }

    public function scopeDePropietario($query, $userId)
    {
        return $query->where('propietario_id', $userId);
    }

    public function necesitaPracticaje()
    {
        return $this->tonelaje_bruto > 500;
    }

    public function tiempoEstancia()
    {
        if ($this->fecha_atraque && $this->fecha_salida_prevista) {
            return $this->fecha_atraque->diffInHours($this->fecha_salida_prevista);
        }
        return null;
    }
}