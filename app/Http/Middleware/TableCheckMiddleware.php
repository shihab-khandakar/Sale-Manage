<?php

namespace App\Http\Middleware;

use App\Http\Traits\CommonTrait;
use App\Http\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\URL;

class TableCheckMiddleware
{
    use CommonTrait;
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        //$currentURL = explode('/', URL::current());
        //end($currentURL);
        //$tableName = prev($currentURL);

        if (!in_array($request->route('table'), $this->tableCheck())) {
            return $this->errorResponse(404, "Invalid Request", []);
        }

        // Post-Middleware Action

        return $next($request);
    }
}
