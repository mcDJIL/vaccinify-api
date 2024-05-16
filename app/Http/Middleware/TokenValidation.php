<?php

namespace App\Http\Middleware;

use App\Models\Societies;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->query('token');

        $society = Societies::where('login_tokens', $token)->first();

        if (!$society || $token === null) return response()->json([ 'message' => 'Invalid token' ], 401);

        return $next($request);
    }
}
