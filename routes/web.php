<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PanelController;
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

// Zona del administrador
Route::middleware(['auth', RolMiddleware::class.':admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [PanelController::class, 'admin'])->name('admin.inicio');
    });

// Zona del cliente
Route::middleware(['auth', RolMiddleware::class.':cliente'])
    ->prefix('cliente')
    ->group(function () {
        Route::get('/', [PanelController::class, 'cliente'])->name('cliente.inicio');
    });
