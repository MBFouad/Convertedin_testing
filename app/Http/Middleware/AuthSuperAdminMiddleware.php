<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

/**
 * Class AuthAdminMiddleware
 *
 * @package App\Http\Middleware
 */
class AuthSuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = Auth::user()) {
            if ($user->isSuperAdmin() ||
                $user->can_move_own_units_to_customer ||
                $user->can_move_units_to_customer
            ) {
                return $next($request);
            }
        }

        return redirect('/');
    }
}
