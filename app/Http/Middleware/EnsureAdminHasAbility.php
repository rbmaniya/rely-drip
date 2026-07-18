<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminHasAbility
{
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $admin = $request->user('admin');

        abort_unless($admin && $admin->hasAbility($ability), 403, 'You do not have permission to access this section.');

        return $next($request);
    }
}
