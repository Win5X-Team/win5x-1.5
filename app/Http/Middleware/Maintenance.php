<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Maintenance {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(!Auth::guest() && Auth::user()->global_ban == 1) return response('')->setStatusCode(502);
        if(!Auth::guest() && Auth::user()->is_admin == 1) return $next($request);
        if (file_exists(storage_path().'/meta/server.down')) return response(\view('pages.down'));
        return $next($request);
    }

}
