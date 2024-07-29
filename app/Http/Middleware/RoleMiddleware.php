<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::guard('api')->user();
        if(!$user || $user->__get('role') !== $role){
            return response()->json([
                'message' => 'Do not have permissions to access this route'
            ], 401);
        }
        return $next($request);
    }
}
