<?php

namespace App\Traits;


use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
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

        if ($collection->isEmpty()) {
            return $this->successResponse($collection);
        }

        // Al ser una coleccion del mismo tipo de Modelo, se puede hacer lo siguiente
        // tomar del primer registro retornado el transformer del modelo

        $transformer = $collection->first()->transformer;
        // Filtrando
        $collection = $this->filterData($collection, $transformer);
        // Ordenando la respuesta si se envia el parametro sort_by
        $collection = $this->sortData($collection, $transformer);
        // Paginando de ser necesario
        $collection = $this->paginate($collection);

        $data = $this->transformData($collection, $transformer);
        // CAcehando la respuesta
        $data = $this->cacheResponse($data);


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

    protected function sortData(Collection $collection, $trasnformer)
    {

        // Accedinedo al request global enviado
        if (request()->has('sort_by')) {

            // Usamos la funcion del transformer individual de cada modelo originalAttribute, para hacer la equivalencoa
            // por los campos permitidos de hacer el filtro y con el nombre transformado
            $attribute = $trasnformer::originalAttribute(request()->input('sort_by'));
            // usamos los atributos de la coleccion y la ordenamos
            $collection = $collection->sortBy($attribute);

        }
        return $collection;
    }

    protected function filterData(Collection $collection, $transformer)
    {
        // request()->query() captura todosas las variables get
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);
            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }


    protected function cacheResponse($data)
    {
        // Capturando la url del request
        $url = request()->url();
        $queryParams = request()->query();

        // Ordena por las llaves obtenidas el array
        ksort($queryParams);

        // a partir de un array forma un path de parametros validos
        $queryString = http_build_query($queryParams);

        $fullUrl = $url . '?' . $queryString;

        return Cache::remember($fullUrl, 1440, function () use ($data) {
            return $data;
        });
    }

    private function transformData($data, $transformer)
    {

        $trasnformation = fractal($data, new $transformer);

        return $trasnformation->toArray();
    }

    protected function paginate(Collection $collection)
    {

        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        // Validando si llega a llegar un paginador por la url
        $validator = Validator::make(request()->all(), $rules);
        // Ejecutando la validacion si ocurrio error y lanzando la excepcion
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        $perPage = (request()->has('per_page')) ? (int)request()->input('per_page') : 15;
        // LengthAwarePaginator::resolveCurrentPage();
        // Funcion que permite resolver automaticamente la pagina actual
        $page = LengthAwarePaginator::resolveCurrentPage();

        // Cortando la colecction, en base a la pagina
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();


        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        // le añade a la url, los parametros que se habian enviado adicionales
        $paginated->appends(request()->all());

        return $paginated;
    }

}
