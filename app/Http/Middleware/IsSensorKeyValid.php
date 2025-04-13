<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSensorKeyValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Admin-Token');
        $adminWithTokenExists = User::where('is_admin', true)
            ->where('auth_key', $token)
            ->exists();

        if (!$token || !$adminWithTokenExists) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.unauthenticated'),
            ]);
        }

        return $next($request);
    }
}
