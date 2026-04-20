<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,cashier')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role ?? 'admin';

        if (!in_array($userRole, $roles)) {
            // Cashier trying to access management → redirect to POS
            if ($userRole === 'cashier') {
                return redirect()->route('pos.index');
            }
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
