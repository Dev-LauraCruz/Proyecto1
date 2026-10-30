<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoCupon extends Model
{
    protected $table = 'pedido_cupon';
    protected $primaryKey = 'id_pedido';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_cupon',
        'descuento_aplicado',
    ];

    protected function casts(): array
    {
        return [
            'descuento_aplicado' => 'decimal:2',
        ];
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function cupon()
    {
        return $this->belongsTo(Cupon::class, 'id_cupon', 'id_cupon');
    }
}
