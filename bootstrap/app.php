<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => RoleMiddleware::class,
    ])->redirectUsersTo(function ($request) {
        // Cek peran pengguna dan arahkan ke halaman yang sesuai
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role->value === 'admin') {
                return route('admin.dashboard.index');
            } else if ($user->role->value === 'merchant') {
                return "/";
            } else {
                // return route('home');
                return "/";
            }
        }

        return '/';
    });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
