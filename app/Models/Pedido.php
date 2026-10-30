<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_direccion',
        'fecha_pedido',
        'total',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_pedido' => 'datetime',
            'total' => 'decimal:2',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function direccion()
    {
        return $this->belongsTo(DireccionEnvio::class, 'id_direccion', 'id_direccion');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_pedido', 'id_pedido');
    }

    public function envio()
    {
        return $this->hasOne(Envio::class, 'id_pedido', 'id_pedido');
    }

    public function cuponAplicado()
    {
        return $this->hasOne(PedidoCupon::class, 'id_pedido', 'id_pedido');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'id_pedido', 'id_pedido');
    }
}
