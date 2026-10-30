<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'correo',
        'telefono',
        'contrasena',
        'rol',
    ];

    protected $hidden = ['contrasena'];

    // --- Adaptación del login a las columnas de tu tabla ---

    // La contraseña está en la columna "contrasena" (no "password")
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getAuthPasswordName()
    {
        return 'contrasena';
    }

    // La tabla no tiene columna remember_token
    public function getRememberTokenName()
    {
        return '';
    }

    // --- Utilidades ---

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function nombreCompleto(): string
    {
        return trim("{$this->nombre} {$this->apellido_p} {$this->apellido_m}");
    }

    // --- Relaciones ---

    public function direcciones()
    {
        return $this->hasMany(DireccionEnvio::class, 'id_cliente', 'id_cliente');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cliente', 'id_cliente');
    }
}
