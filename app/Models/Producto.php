<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'id_marca',
        'nombre_producto',
        'descripcion',
        'marca',
        'precio',
        'stock',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'estado' => 'boolean',
        ];
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Se llama marcaRel porque la tabla ya tiene una columna 'marca' (texto).
    public function marcaRel()
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id_marca');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_producto', 'id_producto');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'id_producto', 'id_producto');
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'producto_proveedor', 'id_producto', 'id_proveedor')
            ->withPivot('costo');
    }

    public function promociones()
    {
        return $this->belongsToMany(Promocion::class, 'promocion_producto', 'id_producto', 'id_promocion');
    }
}
