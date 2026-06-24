<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorConfirmed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $hasDevice = $user->twoFactorDevices()->whereNotNull('confirmed_at')->exists();
        if (!$hasDevice) {
            return redirect()->route('two-factor.setup');
        }

        return $next($request);
    }
}

