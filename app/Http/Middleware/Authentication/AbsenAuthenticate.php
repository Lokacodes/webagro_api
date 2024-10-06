<?php

namespace App\Http\Middleware\Authentication;

use App\Http\Libraries\System;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AbsenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::getToken();
            $decode = JWTAuth::getPayLoad($token)->toArray();

            if (@$decode['payload']['group_id'] != 1)
                return System::response(401, [
                    'statusCode' => 401,
                    'message' => 'Hak akses hanya diberikan untuk administrator'
                ]);

            $request->attributes->add((array) @$decode["payload"]);
        } catch (\Throwable $th) {
            return System::response(401, [
                'statusCode' => 401,
                'message' => $th->getMessage(),
            ]);
        }

        return $next($request);
    }
}