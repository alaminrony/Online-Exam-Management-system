<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class GroupExaminer {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!in_array(Auth::user()->group_id, [2])) {
            return redirect('dashboard');
        }
        return $next($request);
    }

}
