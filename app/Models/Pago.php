<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'fecha_pago',
        'metodo_pago',
        'monto',
        'estado_pago',
        'referencia',
    ];

    protected function casts(): array
    {
        return [
            'fecha_pago' => 'datetime',
            'monto' => 'decimal:2',
        ];
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
