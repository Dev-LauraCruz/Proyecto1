<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionEnvio extends Model
{
    protected $table = 'direccion_envio';
    protected $primaryKey = 'id_direccion';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'calle',
        'numero_exterior',
        'numero_interior',
        'colonia',
        'ciudad',
        'estado',
        'codigo_postal',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_direccion', 'id_direccion');
    }
}
