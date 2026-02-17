<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo_servicio',
        'descripcion',
        'precio_base',
        'unidad_cobro',
        'disponible_24h',
        'requiere_reserva',
        'tiempo_estimado_minutos',
        'proveedor',
        'telefono_contacto',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'disponible_24h' => 'boolean',
        'requiere_reserva' => 'boolean',
        'tiempo_estimado_minutos' => 'integer',
    ];

    public function buques()
    {
        return $this->belongsToMany(Buque::class , 'buque_servicio')
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

    public function resenas()
    {
        return $this->hasMany(Resena::class , 'servicio_id');
    }

    public function promedioCalificacion()
    {
        return $this->resenas()->aprobadas()->avg('calificacion');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible_24h', true);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_servicio', $tipo);
    }

    public function calcularPrecio($cantidad = 1)
    {
        switch ($this->unidad_cobro) {
            case 'fijo':
                return $this->precio_base;
            case 'por_hora':
            case 'por_tonelada':
            case 'por_metro':
            case 'por_servicio':
                return $this->precio_base * $cantidad;
            default:
                return $this->precio_base;
        }
    }
}