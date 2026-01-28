<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KasirMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'kasir') {
            abort(403, 'Akses khusus kasir');
        }

        return $next($request);
    }
}
