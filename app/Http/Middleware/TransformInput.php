<?php

namespace App\Http\Middleware;

use Closure;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $trasnformer)
    {

        $trasnformedInput = [];


        foreach ($request->all() as $input => $value) {
            // Hace la equivalencia de lo enviado a los que existe en la Bd con el trasnformer
            $trasnformedInput[$trasnformer::originalAttribute($input)] = $value;
        }



        // dd($trasnformedInput);
        // Modifcando el request, para que los controladores sean camapeces de acceder a los campos que son
        $request->replace($trasnformedInput);

        return $next($request);
    }
}
