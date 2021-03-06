<?php namespace App\Http\Middleware;

use Closure;

class ActivateMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(!\Auth::user()->isActive()){
			return view('common.blocked');
		}

		return $next($request);
	}

}
