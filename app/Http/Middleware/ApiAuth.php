<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class ApiAuth
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
            $header = $request->header('x-api-key');
            if($header == 'e2cfe1ebab87981db56aa5aea4448701'){
                return $next($request);
            }
        } catch (Exception $exception) {
            return response()->json(['status' => false,'message'=>'Unauthorized','status_code'=>401],401);
        }
        return response()->json(['status' => false,'message'=>'Unauthorized','status_code'=>401],401);
        // abort(401, 'This action is unauthorized.');
    }
}
