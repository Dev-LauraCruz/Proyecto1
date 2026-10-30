<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    public $timestamps = false;

    protected $fillable = [
        'nombre_marca',
        'descripcion',
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
        return $this->hasMany(Producto::class, 'id_marca', 'id_marca');
    }
}
