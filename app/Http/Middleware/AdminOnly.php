<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Akses dilarang. Halaman ini hanya untuk Administrator.', 403);
            }
            return response()->view('errors.403', [
                'message' => 'Anda tidak memiliki hak akses (Administrator) untuk membuka halaman ini.'
            ], 403);
        }

        return $next($request);
    }
}
