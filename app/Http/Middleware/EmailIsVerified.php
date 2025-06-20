<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($request->user()->email_verified_at)) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.email_not_verified'),
            ]);
        }

        return $next($request);
    }
}
