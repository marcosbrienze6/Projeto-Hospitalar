<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Se estiver usando JWT
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['error' => true, 'message' => 'Usuário não autenticado.'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Token inválido ou expirado.'], 401);
        }

        return $next($request);
    }
}
