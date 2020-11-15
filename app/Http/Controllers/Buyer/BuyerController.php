<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BuyerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        // Usamos el scope de Passport, y le pasamos en comas, los scopes a usar y validar
        $this->middleware(['scope:read-general'])->only(['index']);

        // can  // verifica policy registrado en el AuthServiceProvider
        // view // es la funcion del policie
        // buyer es un instancia de Buyer, se puede pasar asi, ya que es el mismo nombre que se recibe en el path
        $this->middleware('can:view,buyer')->only(['show']);
    }

    public function __invoke(Request $request)
    {
        //
    }

    public function index()
    {

        // Usando el Gate definido en el padre y heradedado a este controlller
        $this->allowedAminAction();

        // con has pregunta si tiene trabsaccionts
        // $buyers = Buyer::has('transactions')->get();
        // return response()->json(['data' => $buyers], 200);

        $buyers = Buyer::all();  // El global scope limita a que solo sean compradores
        return $this->showAll($buyers);
    }

    public function show(Buyer $buyer)
    {
        // Primero verifica que tenga transactions y despues que exista
        // $buyer = Buyer::has('transactions')->findOrFail($id);

        // return response()->json(['data' => $buyer], 200);
        return $this->showOne($buyer);
    }


}
