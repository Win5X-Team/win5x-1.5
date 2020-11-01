<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role) {
        try {
            switch ($role) {
                case 'admin':
                    if ($request->user()->chat_role < 3) {
                        if ($request->ajax())
                            return response(view('errors.403'))->setStatusCode(403);
                        abort(404);
                    }
                    break;
                case 'moderator':
                    if ($request->user()->chat_role < 2) {
                        if ($request->ajax())
                            return response(view('errors.403'))->setStatusCode(403);
                        abort(404);
                    }
                    break;
                default:
                    return response(view('errors.403'))->setStatusCode(403);
                    break;
            }
            return $next($request);
        } catch(\Exception $e) {
            return response(view('errors.403'))->setStatusCode(403);
        }
    }

}
