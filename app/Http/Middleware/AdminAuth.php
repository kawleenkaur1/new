<?php

namespace App\Http\Middleware;
use Exception;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if(!Auth::check()){
                return Redirect::route('admin_login');
            }
            if ($request->user()->user_type == 1) {
                return $next($request);
            }
        } catch (Exception $exception) {
            // dd('Could not find role ' . $role);
            abort(401, 'This action is unauthorized.');
        }
        abort(401, 'This action is unauthorized.');
        // return $next($request);
    }
}
