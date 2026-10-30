<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompararController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TiendaController;
use App\Http\Middleware\RolMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Raíz: manda a cada quien a su lugar según su rol
Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return redirect()->route(Auth::user()->esAdmin() ? 'admin.inicio' : 'cliente.inicio');
})->name('inicio');

// Solo para visitantes sin sesión
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
    Route::post('/login', [LoginController::class, 'autenticar'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'salir'])
    ->middleware('auth')
    ->name('logout');

// ---------------- Zona del administrador ----------------
Route::middleware(['auth', RolMiddleware::class.':admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [PanelController::class, 'admin'])->name('admin.inicio');
        Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('admin.reporte');
        Route::get('/reportes/ventas/pdf', [ReporteController::class, 'pdf'])->name('admin.reporte.pdf');
    });

// ---------------- Zona del cliente (tienda) ----------------
Route::middleware(['auth', RolMiddleware::class.':cliente'])
    ->prefix('cliente')
    ->group(function () {
        // Catálogo
        Route::get('/', [TiendaController::class, 'index'])->name('cliente.inicio');

        // Carrito
        Route::get('/carrito', [CarritoController::class, 'index'])->name('cliente.carrito');
        Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])->name('cliente.carrito.agregar');
        Route::patch('/carrito/{producto}', [CarritoController::class, 'actualizar'])->name('cliente.carrito.actualizar');
        Route::delete('/carrito/{producto}', [CarritoController::class, 'quitar'])->name('cliente.carrito.quitar');
        Route::delete('/carrito', [CarritoController::class, 'vaciar'])->name('cliente.carrito.vaciar');

        // Comparador
        Route::get('/comparar', [CompararController::class, 'index'])->name('cliente.comparar');
        Route::post('/comparar/{producto}', [CompararController::class, 'alternar'])->name('cliente.comparar.alternar');
        Route::delete('/comparar', [CompararController::class, 'vaciar'])->name('cliente.comparar.vaciar');

        // Pedidos y comprobante PDF
        Route::get('/pedidos', [PedidoController::class, 'index'])->name('cliente.pedidos');
        Route::post('/pedidos', [PedidoController::class, 'store'])->name('cliente.pedidos.store');
        Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->whereNumber('id')->name('cliente.pedidos.show');
        Route::get('/pedidos/{id}/pdf', [PedidoController::class, 'pdf'])->whereNumber('id')->name('cliente.pedidos.pdf');
    });
