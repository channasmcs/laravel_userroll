<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
// use App\Models\Roles;

class MyauthMiddleware {
    protected $auth;
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;


    }
    public function handle($request, Closure $next)
    {

// check user guest or not
        if ($this->auth->guest())
            {
                return response('Unauthorized', 401);
            }
        else
            {
                // check user roll
                if ($request->user()->role_id == 1)
                    {


                        return 'hello you my member';
                    }
                    else
                    {
                        return 'hello you not member';
                    }
            }

        return $next($request);

    }

}
