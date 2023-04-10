<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Clousure;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    // Crear una funcipon handle para que el middleware pueda leer el token del cookie
    // public function handle(Request $request, Closure $next, ...$guards)
    // {
    //     // Si el usuario no esta autenticado
    //     if (!Auth::check()) {
    //         // Si el usuario no tiene el token en el cookie
    //         if (!$request->hasCookie('cookie_token')) {
    //             // Redireccionar al login
    //             return redirect()->route('login');
    //         }
    //         // Si el usuario tiene el token en el cookie
    //         else {
    //             // Obtener el token del cookie
    //             $token = $request->cookie('cookie_token');
    //             // Obtener el usuario
    //             $user = User::where('remember_token', $token)->first();
    //             // Autenticar al usuario
    //             Auth::login($user);
    //         }
    //     }

    //     return $next($request);
    // }
}
