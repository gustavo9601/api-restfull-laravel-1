<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
   public function __construct()
   {
       parent::__construct();


       // Usamos el scope de Passport, y le pasamos en comas, los scopes a usar y validar
       $this->middleware(['scope:read-general'])->only(['index']);

       // can  // verifica policy registrado en el AuthServiceProvider
       // view // es la funcion del policie
       // buyer es un instancia de Buyer, se puede pasar asi, ya que es el mismo nombre que se recibe en el path
       $this->middleware('can:view,buyer')->only(['index']);
   }

    public function index(Buyer $buyer)
    {


        $categories = $buyer->transactions()
            ->with('product.categories') // product model => categories relacion // trea la coleccion de productos con sus categorias
            ->get()
            ->pluck('product.categories')  // Accede a la coleccion product y luego a categories
            ->collapse() // une en una unico arreglo, todos los arreglos que lo contengan
            ->unique('id') // Deja elementos unicos, y se le pasa la columna por la cual debe hacer la validacion de ser unico
            ->values();  // reorganiza los indices de los elementos eliinados por el unique

        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buyer $buyer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buyer $buyer)
    {
        //
    }
}
