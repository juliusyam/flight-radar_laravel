<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                throw new NotFoundHttpException(__('auth.token_failed_locate_user'));
            } else {
                return $next($request);
            }
        } catch (TokenInvalidException) {
            throw new AccessDeniedHttpException(__('auth.token_invalid'));
        } catch (TokenExpiredException) {
            throw new AccessDeniedHttpException(__('auth.token_expired'));
        } catch (JWTException) {
            throw new BadRequestHttpException(__('auth.token_not_found'));
        }
    }
}
