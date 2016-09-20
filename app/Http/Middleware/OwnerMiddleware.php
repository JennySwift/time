<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OwnerMiddleware
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
        $routeParams = $request->route()->parameterNames();

        if (count($routeParams) > 0) {
            //It is a route such as /api/exercises/1
            $resource = $request->route()->parameter($routeParams[0]);

            if (!$this->belongsToUser($resource)) {
                //Not authorised.
                return response([
                    'error' => "Unauthorised"
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        //It is a route such as /api/exercises,
        //or the model belongs to the user
        //so we can pass (continue) the request
        return $next($request);
    }

    /**
     *
     * @param $resource
     * @return bool
     */
    private function belongsToUser($resource)
    {
        if (isset($resource->user_id)) {
            return $resource->user_id === Auth::user()->id;
        }

        else if (isset($resource->user)) {
            return $resource->user === Auth::user();
        }

        return true;
    }
}
