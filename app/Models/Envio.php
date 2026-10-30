<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envio';
    protected $primaryKey = 'id_envio';
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'paqueteria',
        'numero_guia',
        'fecha_envio',
        'fecha_entrega',
        'estado_envio',
    ];

    protected function casts(): array
    {
        return [
            'fecha_envio' => 'datetime',
            'fecha_entrega' => 'datetime',
        ];
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function seguimientos()
    {
        return $this->hasMany(SeguimientoEnvio::class, 'id_envio', 'id_envio');
    }
}
