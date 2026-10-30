<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    public $timestamps = false;

    protected $fillable = [
        'nombre_empresa',
        'nombre_contacto',
        'correo',
        'telefono',
        'direccion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_proveedor', 'id_proveedor', 'id_producto')
            ->withPivot('costo');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'id_proveedor', 'id_proveedor');
    }
}
