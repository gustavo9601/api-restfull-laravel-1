<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{

    public function __construct()
    {
        // Middleware de trasnformacion de respuestas y obtencion de la data
        // $this->middleware(['transform.input:' . CategoryTransformer::class])->only(['store', 'update']);

        $this->middleware(['client.credentials'])->only(['index', 'show']);
        // Middleware de autenticacion
        $this->middleware(['auth:api'])->except(['index', 'show']);
    }

    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Usando el Gate definido en el padre y heradedado a este controlller
        $this->allowedAminAction();

        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];

        $this->validate($request, $rules);

        $category = Category::create($request->all());

        return $this->showOne($category, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {


        // Usando el Gate definido en el padre y heradedado a este controlller
        $this->allowedAminAction();

        // only recibe los parametros que se quieren usar en el request, los demas se descartan
        $category->fill($request->only(['name', 'description']));

        // validando si se cambio el valor de algun campo
        if (!$category->isDirty()) {
            return $this->errorResponse('Debe especificar al menos un valor para actualizar la categoria', 422);
        }

        $category->save();

        return $this->showOne($category, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        // Usando el Gate definido en el padre y heradedado a este controlller
        $this->allowedAminAction();

        $category->delete();

        return $this->showOne($category);
    }
}
