<?php

namespace Parameter\Http\Middlewares;

use Closure;
use Parameter\ParametersManager;

class ParameterMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! ParametersManager::check($request)) {
            return(redirect()->route('parameters.login'));
        }

        return $next($request);
    }

}