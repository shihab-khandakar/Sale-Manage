<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PermissionCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        if ($request->isMethod('get')) {

            //$auth = Auth::user();
            //return $auth;
            //$currentURL = explode('/', URL::current());
            //
            //$apiEndPoint = end($currentURL);
            //return $apiEndPoint;
            //$tableName = prev($currentURL);
        }

        if ($request->isMethod('delete')) {
            //$currentURL = explode('/', URL::current());
            //$apiEndPoint = end($currentURL);
            //return $apiEndPoint;
            //$tableName = prev($currentURL);
        }

        // Post-Middleware Action

        return $next($request);
    }
}
