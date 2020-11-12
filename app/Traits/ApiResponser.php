<?php

namespace App\Traits;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {

        if ($collection->isEmpty()){
            return $this->successResponse($collection);
        }

        // Al ser una coleccion del mismo tipo de Modelo, se puede hacer lo siguiente
        // tomar del primer registro retornado el transformer del modelo

        $transformer = $collection->first()->transformer;
        $data = $this->transformData($collection, $transformer);
        // fractal encierra el resultado en 'data', por lo cual no es necesari especificarlo de nuevo
        return $this->successResponse($data, $code);
    }


    protected function showOne(Model $instance, $code = 200)
    {

        $transformer = $instance->transformer;
        $data = $this->transformData($instance, $transformer);
       // fractal encierra el resultado en 'data', por lo cual no es necesari especificarlo de nuevo
        return $this->successResponse($data, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    private function transformData($data, $transformer){

        $trasnformation = fractal($data, new $transformer);

        return $trasnformation->toArray();
    }

}
