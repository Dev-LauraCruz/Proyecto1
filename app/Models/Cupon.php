<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupon';
    protected $primaryKey = 'id_cupon';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
        'tipo_descuento',
        'valor_descuento',
        'monto_minimo',
        'fecha_inicio',
        'fecha_expiracion',
        'usos_maximos',
        'usos_actuales',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'valor_descuento' => 'decimal:2',
            'monto_minimo' => 'decimal:2',
            'fecha_inicio' => 'datetime',
            'fecha_expiracion' => 'datetime',
            'estado' => 'boolean',
        ];
    }

    public function aplicaciones()
    {
        return $this->hasMany(PedidoCupon::class, 'id_cupon', 'id_cupon');
    }
}
