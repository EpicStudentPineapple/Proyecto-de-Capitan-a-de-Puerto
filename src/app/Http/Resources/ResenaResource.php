<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResenaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'calificacion' => $this->calificacion,
            'comentario' => $this->comentario,
            'fecha_resena' => $this->fecha_resena->toISOString(),
            'aprobado' => $this->aprobado,
            'user' => new UserResource($this->whenLoaded('user')),
            'servicio_nombre' => $this->whenLoaded('servicio', function () {
            return $this->servicio->nombre;
        }),
        ];
    }
}
