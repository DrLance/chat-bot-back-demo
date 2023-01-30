<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        $user = User::whereHas('tokens', function ($q) use ($token) {
            $q->where('token', $token);
        })->first();

        if ($user) {
            Auth::login($user);

            return $next($request);
        }

        return response(['message' => 'Unauthenticated',], 403);
    }
}
