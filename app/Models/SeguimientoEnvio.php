<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoEnvio extends Model
{
    protected $table = 'seguimiento_envio';
    protected $primaryKey = 'id_seguimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_envio',
        'estado',
        'ubicacion',
        'descripcion',
        'fecha_evento',
    ];

    protected function casts(): array
    {
        return [
            'fecha_evento' => 'datetime',
        ];
    }

    public function envio()
    {
        return $this->belongsTo(Envio::class, 'id_envio', 'id_envio');
    }
}
