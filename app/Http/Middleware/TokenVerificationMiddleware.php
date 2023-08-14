<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken();
        $result=JWTToken::VerifyToken($token);
        if($result=="unauthorized"){
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ],200);
        }
        else{
            return $next($request);
        }


    }
}
