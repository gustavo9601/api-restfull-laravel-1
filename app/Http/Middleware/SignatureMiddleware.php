<?php

namespace App\Http\Middleware;

use Closure;


// Este middleware le añadira una cabecera a las respuestas
class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $header = 'X-Name')
    {

        $response = $next($request);

        // Añadiendole cabeceras desdepus de resolver el request
        $response->headers->set($header, config('app.name'));

        return $response;
    }
}
