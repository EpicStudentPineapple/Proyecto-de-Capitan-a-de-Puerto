<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resenas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'servicio_id',
        'tipo',
        'calificacion',
        'comentario',
        'fecha_resena',
        'aprobado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_resena' => 'datetime',
        'aprobado' => 'boolean',
        'calificacion' => 'integer',
    ];

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service being reviewed.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function scopePlataforma($query)
    {
        return $query->where('tipo', 'plataforma');
    }

    public function scopeServicio($query)
    {
        return $query->where('tipo', 'servicio');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('aprobado', true);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('fecha_resena', 'desc');
    }

    /**
     * Validation rules for creating/updating reviews.
     *
     * @return array
     */
    public static function validationRules($tipo = 'servicio')
    {
        return [
            'tipo' => 'required|in:plataforma,servicio',
            'servicio_id' => $tipo === 'servicio' ? 'required|exists:servicios,id' : 'nullable|exists:servicios,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|min:10|max:500',
        ];
    }
}
