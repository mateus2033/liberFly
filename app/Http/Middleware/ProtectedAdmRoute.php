<?php

namespace App\Http\Middleware;

use App\Utils\ConstantMessage\ConstantMessage;
use App\Utils\ErroMensage\ErroMensage;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ProtectedAdmRoute
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
            $this->me();
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException || $e instanceof TokenExpiredException) {
                return response()->json(['status' => 'Token is Expired']);
            }
            return response()->json(['status' => $e->getMessage()]);
        }
        return $next($request);
    }

    public function me()
    {
        $auth = response()->json(auth('api')->user());
        if(!isset($auth->original->permission_id))
        {
            throw new Exception(ConstantMessage::AUTHORIZATION_NOT_FOUND, 401);
        }

        if ($auth->original->permission_id == 2) {
            throw new Exception(ConstantMessage::USER_NOT_PERMISSION, 401);
        }
    }
}
