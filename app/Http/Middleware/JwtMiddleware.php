<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) throw new Exception('User Not Found');
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->respondWithJSON(403, null, 'Token Invalid');
            } else if ($e instanceof TokenExpiredException) {
                return $this->respondWithJSON(403, null, 'Token Expired');
            } else {
                if ($e->getMessage() === 'User Not Found') {
                    return $this->respondWithJSON(403, null, 'User not found');
                }
                return $this->respondWithJSON(403, null, 'Authorization Token Not Found');
            }
        }
        return $next($request);
    }

    /**
     * @param $status
     * @param $data
     * @param $error
     *
     * @return JsonResponse
     */
    protected function respondWithJSON($status, $data, $error)
    {
        $data = [
            'status' => $status,
            'data' => $data,
            'err' => $error,
        ];
        return response()->json($data);
    }
}
