<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsApplicant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'pelamar') {
            if (in_array(auth()->user()->role, ['super_admin', 'recruitment'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return $next($request);
    }
}
