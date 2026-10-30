<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function mostrar()
    {
        return view('auth.login');
    }

    public function autenticar(Request $request)
    {
        $datos = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'correo.required' => 'Escribe tu correo.',
            'correo.email' => 'Escribe un correo válido.',
            'password.required' => 'Escribe tu contraseña.',
        ]);

        if (Auth::attempt(['correo' => $datos['correo'], 'password' => $datos['password']])) {
            $request->session()->regenerate();

            return redirect()->route('inicio');
        }

        return back()
            ->withErrors(['correo' => 'Correo o contraseña incorrectos.'])
            ->onlyInput('correo');
    }

    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
